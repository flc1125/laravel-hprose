<?php

namespace Flc\Laravel\Hprose\Server;

use Hprose\Http\Server as HproseHttpServer;

/**
 * 创建一个简单的 HTTP 服务
 *
 * @author Flc <2020-03-03 14:21:08>
 */
class HttpServer extends AbstractServer implements InterfaceServer
{
    /**
     * 创建一个服务
     *
     * @return \Hprose\Service
     */
    protected function createServer()
    {
        return new HproseHttpServer();
    }
}
