<?php

namespace Espricho\Components\Redis\Providers;

use Espricho\Components\Providers\EnvToConfigProvider;

/**
 * Class RedisEnvToConfigProvider add redis variables to config
 *
 * @package Espricho\Components\Redis\Providers
 */
class RedisEnvToConfigProvider extends EnvToConfigProvider
{
    public const PROVIDE = 'redis_env_variables';

    protected $env = [
         'REDIS_SERVER',
         'REDIS_PASSWORD',
         'REDIS_PORT',
    ];
}
