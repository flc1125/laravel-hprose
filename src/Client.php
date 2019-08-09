<?php

namespace Flc\Laravel\Hprose;

use Hprose\Http\Client as HproseHttpClient;
use InvalidArgumentException;

/**
 * 客户端实例
 *
 * @author Flc <2019-08-01 10:30:48>
 *
 * @example
 *
 *  $app = [
 *      'config' => [
 *          'hprose.client.default' => 'http',
 *          'hprose.client.connections' => [
 *              'http' => [
 *                  'protocol' => 'http',
 *                  'uri'      => 'http://localhost:9001/server.php',
 *                  'async'    => false
 *              ]
 *          ]
 *      ]
 *  ];
 *
 *  $client = new \Flc\Laravel\Hprose\Client($app);
 */
class Client
{
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
        return $this->app['config']['hprose.client.default'];
    }

    /**
     * 通过别名生成连接实例
     *
     * @param string $name
     *
     * @return ElastisearchClient
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Client protocol [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['protocol']).'Protocol';

        if (! method_exists($this, $driverMethod)) {
            throw new InvalidArgumentException("Protocol [{$config['protocol']}] is not supported.");
        }

        return $this->{$driverMethod}($config);
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
        $connections = $this->app['config']['hprose.client.connections'];

        if (! isset($connections[$name])) {
            throw new InvalidArgumentException("Hprose client [{$name}] not configured.");
        }

        return $connections[$name];
    }

    /**
     * 通过配置创建一个 http 协议连接
     *
     * @param array $config
     *
     * @return \Hprose\Client
     */
    protected function createHttpProtocol($config)
    {
        return new HproseHttpClient($config['uri'], $config['async']);
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
