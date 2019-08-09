<?php

namespace Flc\Laravel\Hprose\Routing;

/**
 * 路由集合
 *
 * @author Flc <2019-07-17 10:25:06>
 */
class Route
{
    /**
     * 别名
     *
     * @var string
     */
    protected $name;

    /**
     * action
     *
     * @var string|\Closure
     */
    protected $action;

    /**
     * 选项
     *
     * @var array
     */
    protected $options = array();

    /**
     * 回调函数
     *
     * @var callable
     */
    protected $callback;

    /**
     * 创建一个路由实例
     *
     * @param string          $name
     * @param string|\Closure $action
     * @param array           $options
     *
     * @return void
     */
    public function __construct($name, $action, $options = array())
    {
        $this->name = $name;
        $this->action = $action;
        $this->options = $options;

        $this->parseAction();
    }

    /**
     * 解析处理 action
     *
     * @param string|\Closure $action
     *
     * @return array
     */
    protected function parseAction()
    {
        switch (true) {
            case is_string($this->action):
                $this->callback = $this->convertToCallback($this->action);
                break;

            case is_callable($this->action):
                $this->callback = $this->action;
                break;

            default:
                throw new InvalidArgumentException("[{$this->action} parameter error.]");
                break;
        }
    }

    /**
     * 将 action 转换为回调函数
     *
     * @return array
     */
    protected function convertToCallback($action)
    {
        if (false !== strpos($action, '@')) {
            list($controller, $method) = explode('@', $action, 2);

            return array(new $controller(), $method);
        }

        return $action;
    }

    // 以下为辅助方法

    /**
     * setOptions 方法别名
     *
     * @param array $options
     *
     * @return $this
     */
    public function options($options = array())
    {
        return $this->setOptions($options);
    }

    /**
     * 设置 options 配置
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions($options = array())
    {
        $this->options = $options;

        return $this;
    }

    /**
     * 返回名称
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 返回可执行的 callback
     *
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * 返回 options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
