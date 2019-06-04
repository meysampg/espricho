<?php

namespace Espricho\Components\ElasticSearch\Providers;

use Psr\Log\LoggerInterface;
use Elasticsearch\ClientBuilder;
use Espricho\Components\Helpers\Str;
use Espricho\Components\Application\System;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Contracts\SearchEngineInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Application\Providers\LoggerProvider;

use function sys;

/**
 * Class ElasticSearchProvider provides elastic search service
 *
 * @package Espricho\Components\ElasticSearch\Providers
 */
class ElasticSearchProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         ESEnvToConfigProvider::PROVIDE => ESEnvToConfigProvider::class,
         LoggerInterface::class         => LoggerProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $servers = Str::split(sys()->getConfig('elasticsearch.servers', ''));

        $system->register(ClientBuilder::class)
               ->setFactory([ClientBuilder::class, 'create'])
               ->addMethodCall('setHosts', [$servers])
               ->addMethodCall('setLogger', [new Reference(LoggerInterface::class)])
        ;

        $system->register(SearchEngineInterface::class)
               ->setFactory([new Reference(ClientBuilder::class), 'build'])
               ->setPublic(true)
        ;
    }
}
