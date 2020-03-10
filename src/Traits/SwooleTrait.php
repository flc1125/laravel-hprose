<?php

namespace Flc\Laravel\Hprose\Traits;

/**
 * swoole 工具类
 *
 * @author Flc <2020-03-10 09:41:48>
 */
trait SwooleTrait
{
    /**
     * 设置进程名称
     *
     * @param string $title
     */
    public function setProcessTitle($title)
    {
        if (PHP_OS === 'Darwin') {
            return;
        }

        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($title);
        } elseif (function_exists('swoole_set_process_name')) {
            swoole_set_process_name($title);
        }
    }
}
