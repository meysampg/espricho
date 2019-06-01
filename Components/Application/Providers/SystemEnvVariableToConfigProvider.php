<?php

namespace Espricho\Components\Application\Providers;

use Espricho\Components\Providers\EnvToConfigProvider;

/**
 * Class SystemEnvVariableToConfigProvider
 *
 * @package Espricho\Components\System\Providers
 */
class SystemEnvVariableToConfigProvider extends EnvToConfigProvider
{
    public const PROVIDE = 'system_environmental_variables';

    protected $env = [
         'SYS_ENV',
         'SYS_TIMEZONE',
    ];
}
