<?php

namespace Flc\Laravel\Hprose;

use Flc\Laravel\Hprose\Routing\Router;

/**
 * 服务提供者
 *
 * @author Flc <2019-08-01 16:23:18>
 */
class Servant
{
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
     * 创建实例
     *
     * @param \Hprose\Service                    $server
     * @param \Flc\Laravel\Hprose\Routing\Router $router
     */
    public function __construct($server, Router $router = null)
    {
        $this->server = $server;
        $this->router = $router;
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
}
