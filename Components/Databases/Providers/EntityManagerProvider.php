<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManagerInterface;
use Espricho\Components\Application\System;
use Espricho\Components\Databases\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Espricho\Components\Contracts\ConfigManagerInterface;
use Espricho\Components\Providers\AbstractServiceProvider;

use function sprintf;

/**
 * Class EntityManagerProvider provides EntityManager provider
 *
 * @package Espricho\Components\Databases\Providers
 */
class EntityManagerProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         DatabaseEnvVariablesProvider::PROVIDE => DatabaseEnvVariablesProvider::class,
    ];

    protected $suggestions = [
         ModelUpdatedSubscriberProvider::PROVIDE => ModelUpdatedSubscriberProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $dbParams = $this->getDBConfigurations($system->getConfigManager());
        $configs  = $this->getConfigs($system);

        $system->register(EntityManagerInterface::class)
               ->setFactory([EntityManager::class, 'create'])
               ->setArguments([$dbParams, $configs])
        ;
    }

    /**
     * Get DB information from the DB configurations
     *
     * @param ConfigManagerInterface $configs
     *
     * @return array
     */
    private function getDBConfigurations(ConfigManagerInterface $configs): array
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
     * @param System $system
     *
     * @return \Doctrine\ORM\Configuration
     */
    private function getConfigs(System $system)
    {
        return Setup::createAnnotationMetadataConfiguration(
             $system->getConfig('db.orm.entity_paths'),
             $system->isDevMode(),
             null,
             null,
             false
        );
    }
}
