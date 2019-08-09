# Laravel-Hprose

该项目支持 Laravel 及非 Laravel 项目使用，支持  `php >= 5.3` 版本语法

> **注：** 以下内容，默认你对 Hprose 已经有了一定了解。内容基于 Laravel 项目介绍。

## 安装

- 环境依赖 C 扩展 `hprose`，详见：[Hprose 官网](https://hprose.com)、[Hprose Pecl 扩展](https://github.com/hprose/hprose-php/wiki/附录B-Hprose-的-pecl-扩展)

    > 服务端安装即可，客户端可不安装

    ```sh
    pecl install hprose
    ```

- 安装扩展包

    ```sh
    composer require flc/laravel-hprose
    ```

## 配置

### 服务提供者与门面

> `Laravel >= 5.5` 已增加包自动发现。旧版本，请手动设置

- 在 `config/app.php` 文件下加入服务提供者

    ```php
    'providers' => [

        ...

        Flc\Laravel\Hprose\HproseServiceProvider::class,
    ]
    ```

- 在 `config/app.php` 文件下加入门面

    ```php
    'aliases' => [

        ...

        'HproseRoute' => Flc\Laravel\Hprose\Facades\HproseRoute::class,
        'HproseServer' => Flc\Laravel\Hprose\Facades\HproseServer::class,
        'HproseClient' => Flc\Laravel\Hprose\Facades\HproseClient::class,
    ]
    ```


### 初始化文件

```sh
php artisan hprose:generator
```

执行完成后，会分别生成以下文件：

- 路由服务提供者：`app/Providers/HproseRouteServiceProvider.php`
- 路由文件：`routes/hprose.php` **（该路由文件非 Laravel 路由，下文简称 `Hprose 路由`）**
    
    > Hprose 路由控制器根目录默认在：`app/Http/Controllers/Hprose` 目录下，可自行在 `app/Providers/HproseRouteServiceProvider.php` 调整

在 `config/app.php` 文件下加入默认的 Hprose 路由服务提供者

```php
'providers' => [

    ...

    app\Providers\HproseRouteServiceProvider::class,
]
```

## 发布配置

```sh
php artisan vendor:publish --provider="Flc\Laravel\Hprose\HproseServiceProvider"
```

执行后，会生成配置文件路径：`/config/hprose.php`

## 使用

### 配置说明

配置文件路径：`/config/hprose.php`

```php
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
                'uri'      => 'http://192.168.2.67:9001/api/server',  // 此处为服务端的连接地址
                'async'    => false,
            ),
        ),
    ]
];
```

目前版本，除客户端配置中的远程服务器地址外，其他配置默认即可

>其他均为后续支持 `swoole-http`、`tcp`、`websocket` 等，做提前架设

### 服务端

增加一个 **Laravel 路由**，用于启动 Http 服务，如：

```php
<?php

Route::any('hprose-server', 'HproseController@server');
```

对应控制器：

```php
<?php

namespace App\Http\Controllers;

use HproseServer;
use HproseRoute;

class HproseController
{
    public function server()
    {
        HproseServer::setRouter(HproseRoute::getRouter())->start();
    }
}
```

访问：http://localhost/hprose-server

### Hprose 路由配置

```php
<?php

HproseRoute::add('tests', 'Controller@tests');
HproseRoute::add('tests_one', 'Controller@tests')->option(['...']);
```

> `Controller@tests` 的方法自行定义

### 客户端

```php
<?php

namespace App\Http\Controllers;

use HproseClient;

class HproseController
{
    public function client()
    {
        $result = HproseClient::tests('tests');
        $result = HproseClient::connection('other')->tests('tests');  // 其他连接
        $result = HproseClient::connection()->tests->one('tests');

        print_r($result);
    }
}
```

### 非 Laravel 项目使用

**服务端**

```php
<?php

$router = new \Flc\Laravel\Hprose\Routing\Router;
$router->group(['prefix' => 'tests', 'namespace' => 'App\\Controllers'], function ($router) {
    $router->add('one', 'Controller@one');
    $router->add('two', 'Controller@two')->option(['...']);

    $router->group(['prefix' => 'group'], function ($router) {
        $router->add('one', 'Controller@group_one');

        ...

    });
});

$app = [
    'config' => [
        'hprose.server.default' => 'http',
        'hprose.server.connections' => [
            'http' => [
                'protocol' => 'http',
            ],
        ],
    ],
];

$server = new \Flc\Laravel\Hprose\Server($app);
$server->setRouter($router)->start();
```

**客户端**

```php
<?php
$app = [
    'config' => [
        'hprose.client.default' => 'http',
        'hprose.client.connections' => [
            'http' => [
                'protocol' => 'http',
                'uri'      => 'http://localhost/server.php',
                'async'    => false
            ]
        ]
    ]
];

$client = new \Flc\Laravel\Hprose\Client($app);

print_r($client->tests->one('222').PHP_EOL);
```

## License

MIT
