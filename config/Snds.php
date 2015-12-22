<?php

return [
    'collector' => [
        'name'          => 'Snds',
        'description'   => 'Collects data from Microsoft SNDS to generate events',
        'enabled'       => true,
        'location'      => 'https://postmaster.live.com/snds/ipStatus.aspx',
        'key'           => '',
    ],

    'feeds' => [
        'E-mail address harvesting' => [
            'class'     => 'Harvesting',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [

            ],
        ],
        'Symantec Brightmail' => [
            'class'     => 'RBL Listed',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [

            ],
        ],
        'SpamHaus SBL/XBL' => [
            'class'     => 'RBL Listed',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [

            ],
        ],
        'Blocked due to user complaints or other evidence of spamming' => [
            'class'     => 'SPAM',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [

            ],
        ],
    ],
];
