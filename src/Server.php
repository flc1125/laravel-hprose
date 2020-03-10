<?php

namespace Flc\Laravel\Hprose;

use Flc\Laravel\Hprose\Routing\Router;
use Flc\Laravel\Hprose\Server\HttpServer;
use Flc\Laravel\Hprose\Server\SocketServer;
use Flc\Laravel\Hprose\Server\SwooleServer;
use InvalidArgumentException;

/**
 * 客户端实例
 *
 * @author Flc <2019-08-01 15:27:34>
 *
 * @example
 *
 *  function bbb($value)
 *  {
 *      return 'bbb:'.$value;
 *  }
 *
 *  $router = new Router();
 *  $router->addRoute('name', 'bbb');
 *
 *  $app = [
 *      'config' => [
 *          'hprose.server.default' => 'http',
 *          'hprose.server.connections' => [
 *              'http' => [
 *                  'protocol' => 'http',
 *              ]
 *          ]
 *      ]
 *  ];
 *
 *  $server = new \Flc\Laravel\Hprose\Server($app)->setRouter($router);
 *  $server->start()
 */
class Server
{
    /**
     * @var \Illuminate\Foundation\Application|array
     */
    protected $app;

    /**
     * 已连接的实例
     *
     * @var array
     */
    protected $connections = array();

    /**
     * 创建一个客户端实例
     *
     * @param \Illuminate\Foundation\Application|array $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 获取一个单例连接
     *
     * @param string $name
     *
     * @return \Flc\Laravel\Elasticsearch\ElasticsearchConnection
     */
    public function connection($name = null)
    {
        $name = isset($name) ? $name : $this->getDefaultConnection();

        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolve($name);
        }

        return $this->connections[$name];
    }

    /**
     * 返回默认的连接名
     *
     * @return string
     */
    protected function getDefaultConnection()
    {
        return $this->app['config']['hprose.server.default'];
    }

    /**
     * 通过别名生成连接实例
     *
     * @param string $name
     *
     * @return \Flc\Laravel\Hprose\Server\InterfaceServer
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Server protocol [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['protocol']).'Protocol';

        if (! method_exists($this, $driverMethod)) {
            throw new InvalidArgumentException("Protocol [{$config['protocol']}] is not supported.");
        }

        return $this->{$driverMethod}($config);
    }

    /**
     * 通过配置创建一个 http 协议连接
     *
     * @param array $config
     *
     * @return \Flc\Laravel\Hprose\Server\HttpServer
     */
    protected function createHttpProtocol($config)
    {
        return new HttpServer($config);
    }

    /**
     * 通过配置创建一个 socket 协议连接
     *
     * @param array $config
     *
     * @return \Flc\Laravel\Hprose\Server\SocketServer
     */
    protected function createSocketProtocol($config)
    {
        return new SocketServer($config);
    }

    /**
     * 通过配置创建一个 swoole 协议连接
     *
     * @param array $config
     *
     * @return \Flc\Laravel\Hprose\Server\SwooleServer
     */
    protected function createSwooleProtocol($config)
    {
        return new SwooleServer($config);
    }

    /**
     * 获取配置
     *
     * @param string $name 配置别名
     *
     * @return array
     */
    protected function getConfig($name)
    {
        $connections = $this->app['config']['hprose.server.connections'];

        if (! isset($connections[$name])) {
            throw new InvalidArgumentException("Hprose client [{$name}] not configured.");
        }

        return $connections[$name];
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->connection(), $method), $parameters);
    }
}
