<?php

namespace AbuseIO\Collectors;

class Snds extends Collector
{
    /**
     * Create a new Microsoft SNDS instance
     */
    public function __construct()
    {
        // Call the parent constructor to initialize some basics
        parent::__construct();
    }

    /**
     * Parse attachments
     * @return array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        if (strlen(config('main.collectors.snds.collector.key')) < 10) {
            $this->failed('Invalid SNDS key');
        }

    }
}
