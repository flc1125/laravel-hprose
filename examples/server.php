<?php

require_once __DIR__.'/../vendor/autoload.php';

use Flc\Laravel\Hprose\Routing\Router;

class Controller
{
    public function tests($tests)
    {
        return 'Hello :::'.$tests;
    }

    public function aaa()
    {
        return time();
    }
}

function bbb($value)
{
    return 'bbdddbcdd11c:'.$value;
}

$router = new Router();
$router->addRoute('name', 'bbb');
// $router->addRoute('name', '\Examples\Controllers\AController@tests');
// $router->addRoute('name1', '\Examples\Controllers\AController@tests');

$router->group(array('prefix' => 'tests', 'namespace' => '\Examples'), function ($router) {
    $router->group(array('prefix' => 'ccc', 'namespace' => 'Controllers'), function ($router) {
        $router->addRoute('aaa', 'AController@tests');
    });
});

$router->group(array('prefix' => 'testsbbb'), function ($router) {
    $router->group(array('prefix' => 'ccc'), function ($router) {
        $router->addRoute('aaa', '\Examples\Controllers\AController@tests');
    });
});

// foreach ($router->getRoutes() as $route) {
//     print_r($route->getName().PHP_EOL);
//     print_r($route->getCallback());
// }

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
                    'max_request' => 100,
                ]
            ),
        ),
    ),
);
$server = new \Flc\Laravel\Hprose\Server($app);
$server->setRouter($router)->start();
