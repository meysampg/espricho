<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Cache\CacheFactory;
use Doctrine\ORM\Cache\CacheConfiguration;
use Espricho\Components\Application\System;
use Doctrine\ORM\Cache\RegionsConfiguration;
use Symfony\Component\DependencyInjection\Reference;
use Doctrine\ORM\Cache\Logging\StatisticsCacheLogger;
use Espricho\Components\Providers\AbstractServiceProvider;

class DoctrineCacheConfigurationProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         RegionsConfiguration::class  => SecondLevelCacheProvider::class,
         StatisticsCacheLogger::class => StatisticsCacheLoggerProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(CacheConfiguration::class, CacheConfiguration::class)
               ->addMethodCall('setCacheFactory', [new Reference(CacheFactory::class)])
               ->addMethodCall('setRegionsConfiguration', [new Reference(RegionsConfiguration::class)])
               ->addMethodCall('setCacheLogger', [new Reference(StatisticsCacheLogger::class)])
               ->setPublic(true)
        ;
    }
}
