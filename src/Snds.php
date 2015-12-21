<?php

namespace AbuseIO\Collectors;

class Snds extends Collector
{
    /**
     * Create a new Abusehub instance
     */
    public function __construct()
    {
        // Call the parent constructor to initialize some basics
        parent::__construct();
    }

    /**
     * Parse attachments
     * @return Array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {

    }
}
