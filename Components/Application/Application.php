<?php

namespace Espricho\Components\Application;

use Exception;
use Espricho\Components\Configs\ConfigCollection;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Application provides the core application class
 *
 * @package Espricho\Components\Application
 */
class Application extends ContainerBuilder
{
    /**
     * Set of application configurations
     *
     * @var ConfigCollection
     */
    protected $configs;

    /**
     * Application constructor.
     *
     * @param ConfigCollection           $configs
     * @param ParameterBagInterface|null $parameterBag
     */
    public function __construct(
         ConfigCollection $configs,
         ParameterBagInterface $parameterBag =
         null
    ) {
        $this->configs = $configs;

        parent::__construct($parameterBag);
    }

    /**
     * fire up the application
     *
     * @return mixed
     * @throws Exception
     */
    public function fire()
    {
        return $this->get(KernelInterface::class)->fire();
    }

    /**
     * Application configurations setter
     *
     * @param ConfigCollection $config
     *
     * @return $this
     */
    public function setConfigs(ConfigCollection $config)
    {
        $this->configs = $config;

        return $this;
    }

    /**
     * Application configurations getter
     *
     * @return ConfigCollection|null
     */
    public function getConfigs(): ?ConfigCollection
    {
        return $this->configs;
    }
}
