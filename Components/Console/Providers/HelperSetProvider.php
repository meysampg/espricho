<?php

namespace Espricho\Components\Console\Providers;

use Symfony\Component\Console\Helper\HelperSet;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Espricho\Components\Databases\Providers\ConnectionHelperProvider;
use Espricho\Components\Databases\Providers\EntityManagerHelperProvider;
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
         EntityManagerHelper::class => EntityManagerHelperProvider::class,
         QuestionHelper::class      => QuestionHelperProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $app)
    {
        $app->register(HelperSet::class, HelperSet::class)
            ->addArgument(
                 [
                      "db"            => $app->get(ConnectionHelper::class),
                      "configuration" => $app->get(ConfigurationHelper::class),
                      "em"            => $app->get(EntityManagerHelper::class),
                      "question"      => $app->get(QuestionHelper::class),
                 ]
            )
        ;

        $app->get(KernelInterface::class)
            ->setHelperSet($app->get(HelperSet::class))
        ;
    }
}
