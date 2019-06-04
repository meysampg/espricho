<?php

namespace Espricho\Components\ElasticSearch\Providers;

use Elasticsearch\Client;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\HttpClientInterface;
use Espricho\Components\Contracts\SearchEngineInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\HttpClient\Providers\HttpClientProvider;

/**
 * Class ElasticSearchProvider provides elastic search service
 *
 * @package Espricho\Components\ElasticSearch\Providers
 */
class ElasticSearchProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         HttpClientInterface::class     => HttpClientProvider::class,
         ESEnvToConfigProvider::PROVIDE => ESEnvToConfigProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(SearchEngineInterface::class, Client::class);
    }
}
