<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\System;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;

/**
 * Class ConfigurationHelperProvider register ConfigurationHelper service
 *
 * @package Espricho\Components\Databases\Providers
 */
class ConfigurationHelperProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         Configuration::class    => MigrationConfigurationProvider::class,
         ConnectionHelper::class => ConnectionHelperProvider::class,
    ];

    public function register(System $app)
    {
        $app->register(ConfigurationHelper::class, ConfigurationHelper::class)
            ->setArguments(
                 [
                      $app->get(ConnectionHelper::class)->getConnection(),
                      $app->get(Configuration::class),
                 ]
            )
        ;
    }
}
