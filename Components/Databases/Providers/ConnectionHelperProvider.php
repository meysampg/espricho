<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Espricho\Components\Application\System;
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
         EntityManagerInterface::class => EntityManagerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(ConnectionHelper::class, ConnectionHelper::class)
               ->addArgument($system->get(EntityManagerInterface::class)->getConnection())
        ;
    }
}
