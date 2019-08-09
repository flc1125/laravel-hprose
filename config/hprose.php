<?php

return [
    'server' => [
        'default'     => 'http',
        'connections' => [
            'http' => [
                'protocol' => 'http',
            ],
        ],
    ],

    'client' => [
        'default'     => 'http',
        'connections' => array(
            'http' => array(
                'protocol' => 'http',
                'uri'      => 'http://192.168.2.67:9001/api/server',
                'async'    => false,
            ),
        ),
    ]
];
