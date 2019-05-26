<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Espricho\Components\Application\Application;
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
         ConnectionHelper::class => ConnectionHelperProvider::class,
    ];

    public function register(Application $app)
    {
        $app->register(ConfigurationHelper::class, ConfigurationHelper::class)
            ->addArgument($app->get(ConnectionHelper::class)->getConnection())
        ;
    }
}
