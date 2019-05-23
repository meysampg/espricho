<?php

namespace Espricho\Components\Configs;

use Symfony\Component\Config\FileLocator;

/**
 * Class YamlConfigLoader provides loading YAML configuration files
 * functionality
 *
 * @package Espricho\Components\Configs
 */
class YamlConfigLoader
{
    private $configs;

    /**
     * YamlConfigLoader constructor provides functionality to load a
     * YAML configurations file
     *
     * @param string $dir
     * @param string $file
     *
     * @throws \Exception
     */
    public function __construct(string $dir, string $file)
    {
        $fileLocator = new FileLocator([$dir]);
        $loader      = new YamlFileLoader($fileLocator);

        $this->configs = $loader->load($file);
    }

    /**
     * Return the loaded configuration set
     *
     * @return ConfigCollection
     */
    public function getConfigs(): ConfigCollection
    {
        return $this->configs;
    }
}
