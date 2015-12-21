<?php

return [
    'collector' => [
        'name'          => 'Snds',
        'enabled'       => true,
    ],

    'feeds' => [
        'Default' => [
            'class'     => 'test',
            'type'      => 'Abuse',
            'enabled'   => true,
            'fields'    => [
                'test'  => 'test',
            ],
        ],
    ],
];
