<?php

namespace Flc\Laravel\Hprose\Routing;

/**
 * 路由 group 类
 *
 * @author Flc <2019-07-30 09:53:08>
 */
class RouteGroup
{
    /**
     * 新 group 配置合并
     *
     * @param array $new
     * @param array $old
     *
     * @return array
     */
    public static function merge($new, $old)
    {
        return array(
            'namespace' => static::formatNamespace($new, $old),
            'prefix'    => static::formatPrefix($new, $old),
        );
    }

    /**
     * Format the namespace for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string|null
     */
    protected static function formatNamespace($new, $old)
    {
        if (isset($new['namespace'])) {
            return isset($old['namespace']) && strpos($new['namespace'], '\\') !== 0
                        ? trim($old['namespace'], '\\').'\\'.trim($new['namespace'], '\\')
                        : trim($new['namespace'], '\\');
        }

        return isset($old['namespace']) ? $old['namespace'] : null;
    }

    /**
     * Format the prefix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string|null
     */
    protected static function formatPrefix($new, $old)
    {
        $old = isset($old['prefix']) ? $old['prefix'] : null;

        return isset($new['prefix']) ? trim($old, '_').'_'.trim($new['prefix'], '_') : $old;
    }
}
