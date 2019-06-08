<?php

namespace Espricho\Components\ElasticSearch\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Contracts\SearchEngineInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\ElasticSearch\Commands\ESInformationCommand;
use Espricho\Components\ElasticSearch\Commands\ESSyncDatabaseCommand;
use Espricho\Components\ElasticSearch\Commands\CreateESMappingCommand;

/**
 * Class ElasticCommandsProvider add elastic search commands to the console
 *
 * @package Espricho\Components\ElasticSearch\Providers
 */
class ElasticCommandsProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         SearchEngineInterface::class => ElasticSearchProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->get(KernelInterface::class)->add(new ESInformationCommand);
        $system->get(KernelInterface::class)->add(new CreateESMappingCommand);
        $system->get(KernelInterface::class)->add(new ESSyncDatabaseCommand);
    }
}
