<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\System;
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
    public function register(System $system)
    {
        $system->get(KernelInterface::class)->add(new GenerateCommand());
        $system->get(KernelInterface::class)->add(new DiffCommand());
        $system->get(KernelInterface::class)->add(new MigrateCommand());
        $system->get(KernelInterface::class)->add(new RollupCommand());
        $system->get(KernelInterface::class)->add(new StatusCommand());
        $system->get(KernelInterface::class)->add(new DumpSchemaCommand());
    }
}
