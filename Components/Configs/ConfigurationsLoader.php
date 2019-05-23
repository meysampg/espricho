<?php

namespace Espricho\Components\Configs;

use Exception;

use function is_null;

/**
 * Class ConfigurationsLoader load configurations for application bootstrap
 *
 * @package Espricho\Bootstrap
 */
class ConfigurationsLoader
{
    /**
     * @var string
     */
    protected $dir;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var ConfigCollection
     */
    protected $configs;

    /**
     * ConfigurationsLoader constructor.
     *
     * @param string $dir
     * @param array  $files
     */
    public function __construct(string $dir, array $files)
    {
        $this->dir   = $dir;
        $this->files = $files;
    }

    /**
     * Load configurations
     *
     * @return ConfigCollection
     */
    public function load(): ConfigCollection
    {
        if (!is_null($this->configs)) {
            return $this->configs;
        }

        $configs = new ConfigCollection();

        foreach ($this->files as $file) {
            try {
                $yamlLoader = new YamlConfigLoader($this->dir, $file);
                $configs->addCollection($yamlLoader->getConfigs());
            } catch (Exception $e) {
                // for the time being, it's okay! :))
            }
        }

        return $this->configs = $configs;
    }
}
