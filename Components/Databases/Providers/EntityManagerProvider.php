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
        $dbParams = $this->getDBConfigurations($app->getConfigs());
        $configs  = $this->getConfigs($app);

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
    private function getDBConfigurations(ConfigCollection $configs): array
    {
        $db = [
             'driver'   => sprintf("pdo_%s", $configs->get('db.driver')),
             'user'     => $configs->get('db.username'),
             'password' => $configs->get('db.password'),
             'dbname'   => $configs->get('db.database'),
             'host'     => $configs->get('db.host'),
             'port'     => $configs->get('db.port'),
        ];

        return $this->normalizeDBParams($db);
    }

    /**
     * Normalize the given db information
     *
     * @param array $db
     *
     * @return array
     */
    private function normalizeDBParams(array $db): array
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

    /**
     * Return config metadata based on annotation
     *
     * @param Application $app
     *
     * @return \Doctrine\ORM\Configuration
     */
    private function getConfigs(Application $app)
    {
        return Setup::createAnnotationMetadataConfiguration(
             $app->getConfig('db.orm.entity_paths'),
             $app->getConfig('app.debug'),
             null,
             null,
             false
        );
    }
}
