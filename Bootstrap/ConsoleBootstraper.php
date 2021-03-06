<?php

namespace Espricho\Bootstrap;

use Espricho\Components\Application\Bootstraper;
use Espricho\Components\Console\Providers\ConsoleProvider;
use Espricho\Components\Auth\Providers\AuthCommandsProvider;
use Espricho\Components\Console\Providers\HelperSetProvider;
use Espricho\Components\Databases\Providers\OrmCommandProvider;
use Espricho\Components\Databases\Providers\OrmProxyCommandProvider;
use Espricho\Components\Databases\Providers\OrmCacheCommandProvider;
use Espricho\Components\Databases\Providers\MigrationCommandProvider;
use Espricho\Components\ElasticSearch\Providers\ElasticCommandsProvider;
use Espricho\Components\Databases\Providers\CacheStatisticsCommandProvider;

/**
 * Class ConsoleBootstraper provides console bootstraper
 */
class ConsoleBootstraper extends Bootstraper
{
    /**
     * @inheritdoc
     */
    public function serviceProviders(): array
    {
        return [
             ConsoleProvider::class,
             HelperSetProvider::class,
             OrmCommandProvider::class,
             AuthCommandsProvider::class,
             ElasticCommandsProvider::class,
             OrmProxyCommandProvider::class,
             OrmCacheCommandProvider::class,
             MigrationCommandProvider::class,
             CacheStatisticsCommandProvider::class,
        ];
    }

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
    public function bootParameters(): array
    {
        return [];
    }
}
