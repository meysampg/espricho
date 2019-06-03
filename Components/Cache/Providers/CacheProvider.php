<?php

namespace Espricho\Components\Cache\Providers;

use Doctrine\Common\Cache\RedisCache;
use Espricho\Components\Application\System;
use Symfony\Contracts\Cache\CacheInterface;
use Espricho\Components\Contracts\RedisInterface;
use Espricho\Components\Redis\Providers\RedisProvider;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Contracts\DoctrineCacheProviderInterface;

/**
 * Class CacheProvider register the cache system
 *
 * @package Espricho\Components\Cache\Providers
 */
class CacheProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         RedisInterface::class => RedisProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->setAlias(CacheInterface::class, RedisInterface::class)
               ->setPublic(true)
        ;

        $system->setAlias(DoctrineCacheProviderInterface::class, RedisCache::class);
    }
}
