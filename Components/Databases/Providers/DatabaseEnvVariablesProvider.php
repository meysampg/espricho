<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Providers\EnvToConfigProvider;

/**
 * Class DatabaseEnvVariablesProvider
 *
 * @package Espricho\Components\Databases\Providers
 */
class DatabaseEnvVariablesProvider extends EnvToConfigProvider
{
    public const PROVIDE = 'database_environmental_variables';

    protected $env = [
         'DB_DRIVER',
         'DB_HOST',
         'DB_PORT',
         'DB_USERNAME',
         'DB_PASSWORD',
         'DB_DATABASE',
    ];
}
