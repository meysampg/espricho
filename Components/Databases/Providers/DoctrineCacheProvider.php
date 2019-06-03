<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\Common\Cache\RedisCache;
use Espricho\Components\Application\System;
use Symfony\Contracts\Cache\CacheInterface;
use Espricho\Components\Cache\Providers\CacheProvider;
use Espricho\Components\Contracts\DoctrineCacheInterface;
use Espricho\Components\Providers\AbstractServiceProvider;

class DoctrineCacheProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         CacheInterface::class => CacheProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->setAlias(DoctrineCacheInterface::class, RedisCache::class);
    }
}
