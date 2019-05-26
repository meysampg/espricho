<?php

namespace Espricho\Components\Console\Providers;

use Symfony\Component\Console\Helper\HelperSet;
use Espricho\Components\Application\Application;
use Espricho\Components\Contracts\KernelInterface;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Espricho\Components\Databases\Providers\ConnectionHelperProvider;
use Espricho\Components\Databases\Providers\ConfigurationHelperProvider;

/**
 * Class HelperSetProvider provides a helper set for console
 *
 * @package Espricho\Components\Console\Providers
 */
class HelperSetProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         KernelInterface::class     => ConsoleProvider::class,
         ConnectionHelper::class    => ConnectionHelperProvider::class,
         ConfigurationHelper::class => ConfigurationHelperProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(HelperSet::class, HelperSet::class)
            ->addArgument(
                 [
                      "db"            => $app->get(ConnectionHelper::class),
                      "configuration" => $app->get(ConfigurationHelper::class),
                 ]
            )
        ;

        $app->get(KernelInterface::class)
            ->setHelperSet($app->get(HelperSet::class))
        ;
    }
}
