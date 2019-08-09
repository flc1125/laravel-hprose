<?php

namespace Flc\Laravel\Hprose\Routing;

/**
 * 路由集合
 *
 * @author Flc <2019-07-17 10:25:06>
 */
class RouteCollection implements \IteratorAggregate, \Countable
{
    /**
     * 路由集合
     *
     * @var Route[]
     */
    protected $routes = array();

    /**
     * 增加一个路由
     *
     * @param string $name  路由别名
     * @param Route  $route 路由
     *
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes[$route->getName()] = $route;

        return $this;
    }

    /**
     * 移除路由
     *
     * @param string $name 别名
     *
     * @return $this
     */
    public function remove($name)
    {
        foreach ((array) $name as $n) {
            unset($this->routes[$n]);
        }

        return $this;
    }

    /**
     * 返回指定名字的路由
     *
     * @param string $name
     *
     * @return Route
     */
    public function get($name)
    {
        return isset($this->routes[$name]) ? $this->routes[$name] : null;
    }

    /**
     * 返回路由集合
     *
     * @return array
     */
    public function all()
    {
        return $this->routes;
    }

    public function count()
    {
        return count($this->routes);
    }

    /**
     * 迭代器
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * 克隆魔术方法
     *
     * @return array
     */
    public function __clone()
    {
        foreach ($this->routes as $name => $route) {
            $this->routes[$name] = clone $route;
        }
    }
}
