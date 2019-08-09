<?php

require_once __DIR__.'/../vendor/autoload.php';

use Flc\Laravel\Hprose\Routing\Route;
use Flc\Laravel\Hprose\Routing\RouteCollection;
use Flc\Laravel\Hprose\Routing\Router;

// $collect = new RouteCollection;

// $route1 = new Route;
// $route2 = new Route;

// $collect->add('route1', $route1);
// $collect->add('route2', $route2);

// print_r($collect->all());

// print_r(count($collect));

// foreach ($collect as $name => $route) {
//     echo $name;
// }

$router = new Router();
$router->addRoute('name', '\Examples\Controllers\AController@tests');
$router->addRoute('name1', '\Examples\Controllers\AController@tests');

$router->group(['prefix' => 'tests', 'namespace' => '\Examples'], function ($router) {
    $router->group(['prefix' => 'ccc', 'namespace' => 'Controllers'], function ($router) {
        $router->addRoute('aaa', 'AController@tests');
    });
});

$router->group(['prefix' => 'testsbbb'], function ($router) {
    $router->group(['prefix' => 'ccc'], function ($router) {
        $router->addRoute('aaa', '\Examples\Controllers\AController@tests');
    });
});

print_r($router->getRoutes());

foreach ($router->getRoutes() as $route) {
    print_r($route->getName().PHP_EOL);
    print_r($route->getCallback());
}
