<?php

namespace Flc\Laravel\Hprose;

use Flc\Laravel\Hprose\Routing\Router;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Hprose 服务者
 *
 * @author Flc <2019-08-05 15:36:55>
 */
class HproseServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->configureHprose();
        $this->configureCommands();
    }

    /**
     * 发布 hprose 配置
     *
     * @return void
     */
    protected function configureHprose()
    {
        $source = realpath($raw = __DIR__.'/../config/hprose.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes(array($source => config_path('hprose.php')));
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('hprose');
        }

        $this->mergeConfigFrom($source, 'hprose');
    }

    /**
     * 发布命令行
     *
     * @return void
     */
    protected function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(array(
            Commands\Generator::class,
        ));
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('hprose.server', function ($app) {
            return new Server($app);
        });

        $this->app->singleton('hprose.router', function ($app) {
            return new Router($app);
        });

        $this->app->singleton('hprose.client', function ($app) {
            return new Client($app);
        });
    }
}
