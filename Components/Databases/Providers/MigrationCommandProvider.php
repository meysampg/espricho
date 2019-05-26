<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\Application;
use Espricho\Components\Contracts\KernelInterface;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Espricho\Components\Console\Providers\ConsoleProvider;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;

/**
 * Class MigrationCommandProvider provides console migration command
 *
 * @package Espricho\Components\Databases\Providers
 */
class MigrationCommandProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         KernelInterface::class => ConsoleProvider::class,
         Configuration::class   => MigrationConfigurationProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get(KernelInterface::class)->add(new GenerateCommand());
        $app->get(KernelInterface::class)->add(new DiffCommand());
        $app->get(KernelInterface::class)->add(new MigrateCommand());
        $app->get(KernelInterface::class)->add(new RollupCommand());
        $app->get(KernelInterface::class)->add(new StatusCommand());
        $app->get(KernelInterface::class)->add(new DumpSchemaCommand());
    }
}
