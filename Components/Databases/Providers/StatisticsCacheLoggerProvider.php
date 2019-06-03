<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Cache\Logging\StatisticsCacheLogger;
use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;

class StatisticsCacheLoggerProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->register(StatisticsCacheLogger::class, StatisticsCacheLogger::class)
               ->setPublic(true)
        ;
    }
}
