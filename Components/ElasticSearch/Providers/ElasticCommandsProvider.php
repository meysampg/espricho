<?php

namespace Espricho\Components\ElasticSearch\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Contracts\HttpClientInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\HttpClient\Providers\HttpClientProvider;
use Espricho\Components\ElasticSearch\Commands\ESInformationCommand;

/**
 * Class ElasticCommandsProvider add elastic search commands to the console
 *
 * @package Espricho\Components\ElasticSearch\Providers
 */
class ElasticCommandsProvider extends AbstractServiceProvider
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
        $system->get(KernelInterface::class)->add(new ESInformationCommand);
    }
}
