<?php

namespace AbuseIO\Collectors;

use AbuseIO\Models\Incident;
use GuzzleHttp;
use Ddeboer\DataImport\Reader;
use SplFileObject;
use ICF;

/**
 * Class Snds
 * @package AbuseIO\Collectors
 */
class Snds extends Collector
{
    /*
     * The API key used for authentitation
     *
     * @var string
     */
    private $key;

    /*
     * The URL used to collect data
     *
     * @var string
     */
    private $url;

    /**
     * Create a new Microsoft SNDS instance
     *
     */
    public function __construct()
    {
        // Call the parent constructor to initialize some basics
        parent::__construct($this);

        $this->key = config("{$this->configBase}.collector.key");
        $this->url = config("{$this->configBase}.collector.location");
    }

    /**
     * Parse attachments
     *
     * @return array    Returns array with failed or success data
     *                  (See collector-common/src/Collector.php) for more info.
     */
    public function parse()
    {
        if (empty($this->key) || strlen($this->key) < 10) {
            return $this->failed("Invalid SNDS key: {$this->key}");
        }

        if (!filter_var($this->url, FILTER_VALIDATE_URL) === true) {
            return $this->failed("Invalid URL configured: {$this->url}");
        }

        if (!$this->createWorkingDir()) {
            return $this->failed(
                "Unable to create working directory"
            );
        }
        $tempFile = "{$this->tempPath}/snds.csv";

        $client = new GuzzleHttp\Client();

        $res = $client->request(
            'GET',
            "{$this->url}?key={$this->key}",
            [
                'http_errors' => false,
                'save_to' => $tempFile
            ]
        );
        if ($res->getStatusCode() !== 200) {
            return $this->failed("URL collection from {$this->url} resulted in a {$res->getStatusCode()}");
        }

        $csvReports = new Reader\CsvReader(new SplFileObject($tempFile));
        $csvReports->setColumnHeaders(
            [
                'first_ip',
                'last_ip',
                'blocked',
                'feed',
            ]
        );

        foreach ($csvReports as $report) {
            $this->feedName = $report['feed'];

            if ($this->isKnownFeed() && $this->isEnabledFeed()) {

                $firstIP = ICF::InetPtoi($report['first_ip']);
                $lastIP = ICF::InetPtoi($report['last_ip']);

                if (!empty($firstIP) && !empty($lastIP) && $firstIP <= $lastIP) {
                    for ($x = $firstIP; $x <= $lastIP; $x++) {
                        $report['ip'] = ICF::inetItop($x);

                        if ($this->hasRequiredFields($report) === true) {
                            $report = $this->applyFilters($report);

                            $incident = new Incident();
                            $incident->source      = config("{$this->configBase}.collector.name");
                            $incident->source_id   = false;
                            $incident->ip          = $report['ip'];
                            $incident->domain      = false;
                            $incident->uri         = false;
                            $incident->class       = config(
                                "{$this->configBase}.feeds.{$this->feedName}.class"
                            );
                            $incident->type        = config(
                                "{$this->configBase}.feeds.{$this->feedName}.type"
                            );
                            /*
                             * This prevents multiple incidents on the same day. So info
                             * blob has a scan time and this a report time
                             */
                            $incident->timestamp   = strtotime('0:00');
                            $incident->information = json_encode($report);

                            $this->incidents[] = $incident;
                        }
                    }
                }
            }
        }

        return $this->success();
    }
}
