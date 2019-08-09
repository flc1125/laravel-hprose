<?php

namespace Flc\Laravel\Hprose\Routing;

use Closure;

/**
 * 路由设置工具
 *
 * @example
 *
 *      $router = new Router;
 *
 *      $router->action('tests', 'Controller@tests');
 *      $router->action('tests2', 'Controller@tests2')->options([...]);
 *
 * @author Flc <2019-07-17 13:55:45>
 */
class Router
{
    /**
     * 路由集合
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     * 路由组属性，堆栈
     *
     * @var array
     */
    protected $groupStack = array();

    /**
     * 创建路由实例
     *
     * @author Flc <2019-07-17 13:59:33>
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * addRoute 方法的别名
     *
     * @param string $name
     * @param mixed  $action
     */
    public function add($name, $action)
    {
        return $this->addRoute($name, $action);
    }

    /**
     * 追加路由
     *
     * @param string $name   别名
     * @param mixed  $action
     *
     * @return Route
     */
    public function addRoute($name, $action)
    {
        $this->routes->add(
            $route = $this->createRoute(
                $this->prependGroupPrefix($name),
                $this->convertToControllerAction($action)
            )
        );

        return $route;
    }

    /**
     * 创建一个路由
     *
     * @param string $name
     * @param mixed  $action
     *
     * @return Route
     */
    protected function createRoute($name, $action)
    {
        return new Route($name, $action);
    }

    /**
     * group 追加前缀
     *
     * @param string $name
     *
     * @return string
     */
    protected function prependGroupPrefix($name)
    {
        return trim(trim($this->getLastGroupPrefix(), '_').'_'.trim($name, '_'), '_') ?: '_';
    }

    /**
     * Get the prefix from the last group on the stack.
     *
     * @return string
     */
    protected function getLastGroupPrefix()
    {
        if (! empty($this->groupStack)) {
            $last = end($this->groupStack);

            return isset($last['prefix']) ? $last['prefix'] : '';
        }

        return '';
    }

    /**
     * 追加 namespace
     *
     * @param string|\Closure $action
     *
     * @return string|\Closure
     */
    protected function convertToControllerAction($action)
    {
        if (! is_string($action)) {
            return $action;
        }

        if (! empty($this->groupStack)) {
            $action = $this->prependGroupNamespace($action);
        }

        return $action;
    }

    /**
     * Prepend the last group namespace onto the use clause.
     *
     * @param string $class
     *
     * @return string
     */
    protected function prependGroupNamespace($class)
    {
        $group = end($this->groupStack);

        return isset($group['namespace']) && strpos($class, '\\') !== 0
                ? $group['namespace'].'\\'.$class : $class;
    }

    /**
     * 创建群组路由
     *
     * @param array           $attributes 属性
     * @param \Closure|string $routes     回调|路由路径
     *
     * @return void
     */
    public function group(array $attributes, $routes)
    {
        $this->updateGroupStack($attributes);

        $this->loadRoutes($routes);

        array_pop($this->groupStack);
    }

    /**
     * 更新路由组堆栈
     *
     * @return void
     */
    protected function updateGroupStack(array $attributes)
    {
        if (! empty($this->groupStack)) {
            $attributes = RouteGroup::merge($attributes, end($this->groupStack));
        }

        $this->groupStack[] = $attributes;
    }

    /**
     * 载入路由配置，支持文件或回调
     *
     * @param string|\Closure $routes
     *
     * @return void
     */
    protected function loadRoutes($routes)
    {
        if ($routes instanceof Closure) {
            $routes($this);
        } else {
            $router = $this;

            require $routes;
        }
    }

    /**
     * 返回路由集合, getRouteCollection 别名
     *
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->getRouteCollection();
    }

    /**
     * 返回路由集合
     *
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routes;
    }

    /**
     * 返回当前对象
     *
     * @return $this
     */
    public function getRouter()
    {
        return $this;
    }
}
