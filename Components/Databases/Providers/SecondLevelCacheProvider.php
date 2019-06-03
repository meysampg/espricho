<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Cache\CacheFactory;
use Doctrine\ORM\Cache\DefaultCacheFactory;
use Doctrine\ORM\Cache\RegionsConfiguration;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\DoctrineCacheInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\DependencyInjection\Reference;

class SecondLevelCacheProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(RegionsConfiguration::class, RegionsConfiguration::class)
               ->setPublic(true)
        ;

        $system->register(CacheFactory::class, DefaultCacheFactory::class)
               ->setArguments(
                    [
                         new Reference(RegionsConfiguration::class),
                         new Reference(DoctrineCacheInterface::class),
                    ]
               )
               ->setPublic(true)
        ;

    }
}
