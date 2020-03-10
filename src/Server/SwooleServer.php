<?php

namespace Flc\Laravel\Hprose\Server;

use Flc\Laravel\Hprose\Traits\SwooleTrait;
use Hprose\Swoole\Server as HproseSwooleServer;

/**
 * 创建一个 Swoole 服务
 *
 * @author Flc <2020-03-03 14:38:09>
 */
class SwooleServer extends AbstractServer implements InterfaceServer
{
    use SwooleTrait;

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
    public function start()
    {
        if (is_array($this->config['settings'])) {
            $this->server->set($this->config['settings']);
        }

        $this->server->on('start', array($this, 'onStart'));
        $this->server->on('ManagerStart', array($this, 'onManagerStart'));
        $this->server->on('WorkerStart', array($this, 'onWorkerStart'));

        $this->server->start();
    }

    /**
     * 重载服务
     *
     * @return void
     */
    public function reload()
    {
        $this->server->reload();
    }

    /**
     * 停止服务
     *
     * @return void
     */
    public function stop()
    {

    }

    /**
     * 重启服务
     *
     * @return void
     */
    public function restart()
    {
        $this->stop();
        $this->start();
    }

    /**
     * 启动主进程（master）的回调函数
     *
     * @return mixed
     */
    public function onStart($serv)
    {
        $this->setProcessTitle('master:'.$serv->master_pid);
    }

    /**
     * 启动 manager 进程的回调函数
     *
     * @param  \Swoole\Server $serv
     * @return mixed
     */
    public function onManagerStart($serv)
    {
        $this->setProcessTitle('manager:'.$serv->manager_pid);
    }

    /**
     * 启动 worker 进程的回调函数
     *
     * @param  \Swoole\Server $serv
     * @return mixed
     */
    public function onWorkerStart($serv)
    {
        $this->setRouter($this->router);
        $this->addFunctions();
        $this->setProcessTitle('worker:'.$serv->worker_pid);

        print_r($this->router);
    }
}
