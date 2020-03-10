<?php

namespace Flc\Laravel\Hprose\Server;

use Hprose\Socket\Server as HproseSocketServer;

/**
 * 创建一个简单的 Socket 服务
 *
 * @author Flc <2020-03-03 14:38:09>
 */
class SocketServer extends AbstractServer implements InterfaceServer
{
    /**
     * 创建一个服务
     *
     * @return \Hprose\Service
     */
    protected function createServer()
    {
        return new HproseSocketServer($this->config['uri']);
    }
}
