<?php

namespace Flc\Laravel\Hprose\Server;

use Flc\Laravel\Hprose\Routing\Router;
use Hprose\Http\Server as HproseHttpServer;
use InvalidArgumentException;

/**
 * 服务基类
 *
 * @author Flc <2020-03-03 14:20:56>
 */
abstract class AbstractServer implements InterfaceServer
{
    /**
     * 配置
     *
     * @var array
     */
    protected $config = array();

    /**
     * 服务
     *
     * @var \Hprose\Service
     */
    protected $server;

    /**
     * 路由集合
     *
     * @var \Flc\Laravel\Hprose\Routing\Router
     */
    protected $router;

    /**
     * 创建一个服务
     *
     * @param array                                  $config
     * @param Flc\Laravel\Hprose\Routing\Router|null $router
     */
    public function __construct($config = array(), Router $router = null)
    {
        $this->config = $config;
        $this->router = $router;
        $this->server = $this->createServer();
    }

    /**
     * 创建一个服务
     *
     * @return \Hprose\Service
     */
    protected function createServer()
    {
        throw new InvalidArgumentException("This [createServer] method is not supported.");
    }

    /**
     * 设置路由
     *
     * @param \Flc\Laravel\Hprose\Routing\Router $router
     *
     * @return $this
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * 启动服务
     *
     * @return void
     */
    public function start()
    {
        $this->addFunctions();

        $this->server->start();
    }

    /**
     * 加入方法
     *
     * @return $this->
     */
    protected function addFunctions()
    {
        foreach ($this->router->getRoutes() as $route) {
            $this->server->addFunction($route->getCallback(), $route->getName(), $route->getOptions());
        }
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
        return call_user_func_array(array($this->server, $method), $parameters);
    }
}
