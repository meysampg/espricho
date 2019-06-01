<?php

namespace Espricho\Components\Configs;

use Exception;
use Espricho\Components\Configs\Exceptions\ConfigurationFileNotExists;

use function substr;
use function is_null;
use function sprintf;
use function stripos;
use function array_map;
use function array_merge;

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
     * @throws ConfigurationFileNotExists
     */
    public function load(): ConfigCollection
    {
        if (!is_null($this->configs)) {
            return $this->configs;
        }

        $configs = new ConfigCollection();

        for ($i = 0; $i < count($this->files) && ($file = $this->files[$i]); $i++) {
            try {
                $section = substr($file, 0, stripos($file, '.yaml'));

                $yamlLoader = new YamlConfigLoader($this->dir, $file);
                $configs->addCollection($yamlLoader->getConfigs());

                $loaders = $this->getLoaders($configs->get("{$section}.loader", []));
                $this->files = array_merge($this->files, $loaders);
            } catch (Exception $e) {
                throw new ConfigurationFileNotExists(
                     sprintf(
                          "%s must be located, but does not exists on %s", $file,
                          $this->dir
                     )
                );
            }
        }

        return $this->configs = $configs;
    }

    /**
     * Get other config loaders from a given loader name
     *
     * @param array  $loader
     * @param string $ext
     *
     * @return array
     */
    protected function getLoaders(array $loader, string $ext = 'yaml'): array
    {
        return array_map(
             function ($loader) use ($ext) {
                 return sprintf("%s.%s", $loader, $ext);
             },
             $loader
        );
    }
}
