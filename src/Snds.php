<?php

namespace AbuseIO\Collectors;

use GuzzleHttp;
use Ddeboer\DataImport\Reader;
use SplFileObject;

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
            'GET', "{$this->url}?key={$this->key}",
            [
                'http_errors' => false,
                'save_to' => $tempFile
            ]
        );
        if($res->getStatusCode() !== 200) {
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

                $firstIP = ip2long($report['first_ip']);
                $lastIP = ip2long($report['last_ip']);

                if (!empty($firstIP) && !empty($lastIP) && $firstIP <= $lastIP) {
                    for ($x = $firstIP; $x <= $lastIP; $x++) {
                        $report['ip'] = long2ip($x);
                        $report['timestamp'] = time();

                        if ($this->hasRequiredFields($report) === true) {
                            $report = $this->applyFilters($report);

                            $this->events[] = [
                                'source'        => config("{$this->configBase}.collector.name"),
                                'ip'            => $report['ip'],
                                'domain'        => false,
                                'uri'           => false,
                                'class'         => config(
                                    "{$this->configBase}.feeds.{$this->feedName}.class"
                                ),
                                'type'          => config(
                                    "{$this->configBase}.feeds.{$this->feedName}.type"
                                ),
                                'timestamp'     => $report['timestamp'],
                                'information'   => json_encode($report),
                            ];
                        }
                    }
                }
            }
        }

        return $this->success();
    }
}
