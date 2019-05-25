<?php

namespace Espricho\Components\Singletons;

use Doctrine\ORM\Tools\Setup;
use Espricho\Components\Containers\Singleton;
use Espricho\Components\Configs\ConfigCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Espricho\Components\Databases\EntityManager as BaseEntityManager;

use function sprintf;
use function func_get_args;

class EntityManager extends Singleton
{
    /**
     * @inheritdoc
     */
    protected static $class = BaseEntityManager::class;

    /**
     * Get an instance of EntityManager instance
     *
     * @return object
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getInstance(): object
    {
        if (isset(static::$instance[static::$class])) {
            return static::$instance[static::$class];
        }

        $configs = current(func_get_args());

        $debug    = $configs->get('app.debug');
        $dbParams = static::getDBConfigurations($configs);
        $configs  = Setup::createAnnotationMetadataConfiguration($configs->get('db.orm.entity_paths'), $debug);

        return static::$instance[static::$class] = BaseEntityManager::create($dbParams, $configs);
    }

    /**
     * Get DB information from the DB configurations
     *
     * @param ConfigCollection $configs
     *
     * @return array
     */
    private static function getDBConfigurations(ConfigCollection $configs): array
    {
        $db = [
             'driver'   => sprintf("pdo_%s", $configs->get('db.driver')),
             'user'     => $configs->get('db.username'),
             'password' => $configs->get('db.password'),
             'dbname'   => $configs->get('db.database'),
             'host'     => $configs->get('db.host'),
             'port'     => $configs->get('db.port'),
        ];

        return static::normalizeDBParams($db);
    }

    /**
     * Normalize the given db information
     *
     * @param array $db
     *
     * @return array
     */
    private static function normalizeDBParams(array $db): array
    {
        $default = [
             'driver'   => '',
             'user'     => '',
             'password' => '',
             'dbname'   => '',
             'host'     => '',
             'port'     => '',
        ];

        $resolver = new OptionsResolver();
        $resolver->setDefaults($default);

        return $resolver->resolve($db);
    }
}
