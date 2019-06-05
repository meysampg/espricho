<?php

namespace Espricho\Bootstrap;

use Espricho\Components\Application\Bootstraper;
use Espricho\Components\Http\Providers\HttpKernelProvider;
use Espricho\Components\Application\Providers\LoggerProvider;
use Espricho\Components\Databases\Providers\EntityManagerProvider;
use Espricho\Components\ElasticSearch\Providers\ElasticSearchProvider;

/**
 * Class HttpBootstraper bootstrap HTTP application
 */
class HttpBootstraper extends Bootstraper
{
    /**
     * @inheritdoc
     */
    public function extensions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function serviceProviders(): array
    {
        return [
             LoggerProvider::class,
             HttpKernelProvider::class,
             ElasticSearchProvider::class,
             EntityManagerProvider::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootParameters(): array
    {
        return [];
    }
}
