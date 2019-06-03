<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Cache\CacheConfiguration;
use Espricho\Components\Application\System;
use Espricho\Components\Databases\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Espricho\Components\Contracts\DoctrineCacheInterface;
use Espricho\Components\Contracts\ConfigManagerInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Espricho\Components\Validations\Providers\ValidatorProvider;

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
         ValidatorInterface::class             => ValidatorProvider::class,
         DoctrineCacheInterface::class         => DoctrineCacheProvider::class,
         CacheConfiguration::class             => DoctrineCacheConfigurationProvider::class,
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
               ->setPublic(true)
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
     * @throws \Exception
     */
    private function getConfigs(System $system)
    {
        $configurations = Setup::createAnnotationMetadataConfiguration(
             $system->getConfig('db.orm.entity_paths'),
             $system->isDevMode(),
             $system->getPath('Runtime/Cache/Proxies'),
             $system->get(DoctrineCacheInterface::class),
             false
        );

        $configurations->setSecondLevelCacheEnabled(true);
        $configurations->setSecondLevelCacheConfiguration($system->get(CacheConfiguration::class));

        return $configurations;
    }
}
