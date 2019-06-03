<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\Bundle\DoctrineCacheBundle\Command\StatsCommand;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\DoctrineCacheInterface;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Databases\Commands\CacheStatisticsCommand;
use Espricho\Components\Providers\AbstractServiceProvider;

class CacheStatisticsCommandProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         DoctrineCacheInterface::class => DoctrineCacheProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->get(KernelInterface::class)->add(new CacheStatisticsCommand);
    }
}
