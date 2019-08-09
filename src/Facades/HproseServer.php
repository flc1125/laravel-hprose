<?php

namespace Flc\Laravel\Hprose\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Hprose Server 门面
 *
 * @author Flc <2019-08-05 12:01:34>
 */
class HproseServer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hprose.server';
    }
}
