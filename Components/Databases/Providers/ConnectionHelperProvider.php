<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Databases\EntityManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class ConnectionHelperProvider register ConnectionHelper service
 *
 * @package Espricho\Components\Databases\Providers
 */
class ConnectionHelperProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         EntityManager::class => EntityManagerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(ConnectionHelper::class, ConnectionHelper::class)
               ->addArgument($system->get(EntityManager::class)->getConnection())
        ;
    }
}
