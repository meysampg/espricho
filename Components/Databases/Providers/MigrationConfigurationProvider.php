<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\Application;
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
    public function register(Application $app)
    {
        $app->register(Configuration::class, Configuration::class)
            ->addArgument($app->get(EntityManager::class)->getConnection())
        ;

        $mc = $app->get(Configuration::class);
        $mc->setName($app->getConfig('migration.name', 'Espricho Migration'));
        $mc->setMigrationsNamespace($app->getConfig('migration.namespace', 'Espricho\Migrations'));
        $mc->setMigrationsTableName($app->getConfig('migration.table_name', 'migrations'));
        $mc->setMigrationsColumnName($app->getConfig('migration.column_name', 'version'));
        $mc->setMigrationsColumnLength($app->getConfig('migration.column_length', 255));
        $mc->setMigrationsExecutedAtColumnName($app->getConfig('migration.executed_at_column_name', 'executed_at'));
        $mc->setMigrationsDirectory($app->getConfig('migration.directory', static::getDefaultDirectory()));
        $mc->setAllOrNothing($app->getConfig('migration.all_or_nothing', true));
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
