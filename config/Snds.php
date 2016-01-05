<?php

return [
    'collector' => [
        'name'          => 'Microsoft SNDS',
        'description'   => 'Collects data from Microsoft SNDS to generate events',
        'enabled'       => false,
        'location'      => 'https://postmaster.live.com/snds/ipStatus.aspx',
        'key'           => '',
    ],

    'feeds' => [
        'E-mail address harvesting' => [
            'class'     => 'Harvesting',
            'type'      => 'Abuse',
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
        'Symantec Brightmail' => [
            'class'     => 'RBL Listed',
            'type'      => 'Abuse',
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
        'SpamHaus SBL/XBL' => [
            'class'     => 'RBL Listed',
            'type'      => 'Abuse',
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
        'Blocked due to user complaints or other evidence of spamming' => [
            'class'     => 'SPAM',
            'type'      => 'Abuse',
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
