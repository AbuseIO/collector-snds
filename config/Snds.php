<?php

return [
    'collector' => [
        'name'          => 'Microsoft SNDS',
        'description'   => 'Collects data from Microsoft SNDS to generate events',
        'enabled'       => false,
        'location'      => 'https://postmaster.live.com/snds/ipStatus.aspx',
        'key'           => '',

        'aliasses'        => [
            'E-mail address harvesting' => 'detected_harvesting',
            'Symantec Brightmail'       => 'symantec_rbl',
            'SpamHaus SBL/XBL'          => 'spamhaus_rbl',
            'Blocked due to user complaints or other evidence of spamming' => 'user_complaints',
        ]
    ],

    'feeds' => [
        'detected_harvesting' => [
            'class'     => 'HARVESTING',
            'type'      => 'ABUSE',
            'enabled'   => true,
            'fields'    => [
                'first_ip',
                'last_ip',
                'blocked',
                'feed',
            ],
            'filters'   => [
                'first_ip',
                'last_ip',
            ]
        ],
        'symantec_rbl' => [
            'class'     => 'RBL_LISTED',
            'type'      => 'ABUSE',
            'enabled'   => true,
            'fields'    => [
                'first_ip',
                'last_ip',
                'blocked',
                'feed',
            ],
            'filters'   => [
                'first_ip',
                'last_ip',
            ]
        ],
        'spamhaus_rbl' => [
            'class'     => 'RBL_LISTED',
            'type'      => 'ABUSE',
            'enabled'   => true,
            'fields'    => [
                'first_ip',
                'last_ip',
                'blocked',
                'feed',
            ],
            'filters'   => [
                'first_ip',
                'last_ip',
            ]
        ],
        'user_complaints' => [
            'class'     => 'SPAM',
            'type'      => 'ABUSE',
            'enabled'   => true,
            'fields'    => [
                'first_ip',
                'last_ip',
                'blocked',
                'feed',
            ],
            'filters'   => [
                'first_ip',
                'last_ip',
            ]
        ],
    ],
];
