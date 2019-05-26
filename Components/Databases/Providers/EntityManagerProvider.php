<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Tools\Setup;
use Espricho\Components\Databases\EntityManager;
use Espricho\Components\Application\Application;
use Espricho\Components\Configs\ConfigCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Espricho\Components\Providers\AbstractServiceProvider;

use function sprintf;

/**
 * Class EntityManagerProvider provides EntityManager provider
 *
 * @package Espricho\Components\Databases\Providers
 */
class EntityManagerProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $debug    = $app->getConfig('app.debug');
        $dbParams = static::getDBConfigurations($app->getConfigs());
        $configs  = Setup::createAnnotationMetadataConfiguration($app->getConfig('db.orm.entity_paths'), $debug);

        $app->register(EntityManager::class)
            ->setFactory([EntityManager::class, 'create'])
            ->setArguments([$dbParams, $configs])
        ;
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
