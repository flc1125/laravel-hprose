<?php

namespace Flc\Laravel\Hprose\Server;

use Flc\Laravel\Hprose\Routing\Router;
use Hprose\Swoole\Server as HproseSwooleServer;

/**
 * 创建一个 Swoole 服务
 *
 * @author Flc <2020-03-03 14:38:09>
 */
class SwooleServer extends AbstractServer implements InterfaceServer
{
    /**
     * 创建一个服务
     *
     * @return \Hprose\Service
     */
    protected function createServer()
    {
        return new HproseSwooleServer($this->config['uri']);
    }

    /**
     * 启动服务
     *
     * @return void
     */
    public function start(Router $router)
    {
        $this->addSettings();
        $this->addFunctions($router);

        $this->server->start();
    }

    /**
     * 追加配置
     *
     * @return void
     */
    protected function addSettings()
    {
        if (! is_array($this->config['settings'])) {
            return;
        }

        $this->server->set($this->config['settings']);
    }
}
