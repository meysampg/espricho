<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Databases\EntityManager;
use Doctrine\Migrations\Configuration\Configuration;
use Espricho\Components\Providers\AbstractServiceProvider;

use function sprintf;

/**
 * Class MigrationConfigurationProvider registers migration configurations on service container
 *
 * @package Espricho\Components\Databases\Providers
 */
class MigrationConfigurationProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         EntityManager::class => EntityManagerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(Configuration::class, Configuration::class)
               ->addArgument($system->get(EntityManager::class)->getConnection())
        ;

        $mc = $system->get(Configuration::class);
        $mc->setName($system->getConfig('migration.name', 'Espricho Migration'));
        $mc->setMigrationsNamespace($system->getConfig('migration.namespace', 'Espricho\Migrations'));
        $mc->setMigrationsTableName($system->getConfig('migration.table_name', 'migrations'));
        $mc->setMigrationsColumnName($system->getConfig('migration.column_name', 'version'));
        $mc->setMigrationsColumnLength($system->getConfig('migration.column_length', 255));
        $mc->setMigrationsExecutedAtColumnName($system->getConfig('migration.executed_at_column_name', 'executed_at'));
        $mc->setMigrationsDirectory($system->getConfig('migration.directory', static::getDefaultDirectory()));
        $mc->setAllOrNothing($system->getConfig('migration.all_or_nothing', true));
    }

    /**
     * Create default directory of migration (on Databases/Migrations)
     *
     * @return string
     */
    private function getDefaultDirectory(): string
    {
        $ds = DIRECTORY_SEPARATOR;

        return sprintf("%s{$ds}..{$ds}..{$ds}..{$ds}Databases{$ds}Migrations", __DIR__);
    }
}
