<?php

namespace Espricho\Components\Memcached\Providers;

use Espricho\Components\Providers\EnvToConfigProvider;

/**
 * Class MemcachedEnvToConfigProvider add redis variables to config
 *
 * @package Espricho\Components\Memcached\Providers
 */
class MemcachedEnvToConfigProvider extends EnvToConfigProvider
{
    public const PROVIDE = 'memcached_env_variables';

    protected $env = [
         'MEMCACHED_SERVERS',
    ];
}
