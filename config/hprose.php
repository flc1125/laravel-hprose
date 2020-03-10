<?php

return [
    'server' => [
        'default'     => 'http',
        'connections' => [
            'http' => [
                'protocol' => 'http',
            ],

            'socket-tcp' => [
                'protocol' => 'socket',
                'uri' => 'tcp://0.0.0.0:1314',
            ],

            'socket-unix' => [
                'protocol' => 'socket',
                'uri' => 'unix:/tmp/my.sock',
            ],

            'swoole-tcp' => [
                'protocol' => 'swoole',
                'uri' => 'tcp://0.0.0.0:1314',
            ],

            'swoole-ws' => [
                'protocol' => 'swoole',
                'uri' => 'ws://0.0.0.0:8088',
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
