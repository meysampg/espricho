<?php

namespace Espricho\Components\ElasticSearch\Providers;

use Espricho\Components\Providers\EnvToConfigProvider;

/**
 * Class ESEnvToConfigProvider add elastic search environmental configurations
 * to the configurations.
 *
 * @package Espricho\Components\ElasticSearch\Providers
 */
class ESEnvToConfigProvider extends EnvToConfigProvider
{
    public const PROVIDE = 'elastic_search_env_variables';

    protected $env = [
         'ELASTICSEARCH_SERVERS',
    ];
}
