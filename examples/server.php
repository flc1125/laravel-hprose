<?php

require_once __DIR__.'/../vendor/autoload.php';

use Flc\Laravel\Hprose\Routing\Router;



// foreach ($router->getRoutes() as $route) {
//     print_r($route->getName().PHP_EOL);
//     print_r($route->getCallback());
// }
$router = new Router();

$app = array(
    'config' => array(
        'hprose.server.default'     => 'swoole',
        'hprose.server.connections' => array(
            'http' => array(
                'protocol' => 'http',
            ),

            'socket' => array(
                'protocol' => 'socket',
                'uri'      => 'tcp://0.0.0.0:1314',
            ),

            'swoole' => array(
                'protocol' => 'swoole',
                'uri'      => 'tcp://0.0.0.0:1314',
                'settings' => [
                    'pid_file' => __DIR__.'/server.pid',
                    'worker_num' => 4,
                    // 'daemonize' => 1,
                    'max_request' => 100,
                ]
            ),
        ),
    ),
);
$server = new \Flc\Laravel\Hprose\Server($app);
$server->start($router);
