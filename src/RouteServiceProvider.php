<?php

namespace Flc\Laravel\Hprose;

use Illuminate\Support\ServiceProvider;

/**
 * Route 服务者
 *
 * @author Flc <2019-08-05 15:41:22>
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * 命名空间路径
     *
     * @var string|null
     */
    protected $namespace;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->loadRoutes();
    }

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        if (method_exists($this, 'map')) {
            $this->app->call(array($this, 'map'));
        }
    }
}
