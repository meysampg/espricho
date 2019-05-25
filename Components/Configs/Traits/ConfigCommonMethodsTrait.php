<?php

namespace Espricho\Components\Configs\Traits;

use Espricho\Components\Configs\ConfigCollection;

/**
 * Trait ConfigCommonMethodsTrait shares common behaviour related to application
 * configurations
 *
 * @package Espricho\Components\Configs\Traits
 */
trait ConfigCommonMethodsTrait
{
    /**
     * Contains application configurations collection
     *
     * @var ConfigCollection
     */
    protected $configs;

    /**
     * A setter for ConfigCollection
     *
     * @param ConfigCollection $configs
     *
     * @return ConfigCommonMethodsTrait
     */
    public function setConfigs(ConfigCollection $configs)
    {
        $this->configs = $configs;

        return $this;
    }

    /**
     * A getter of ConfigCollection
     *
     * @return ConfigCollection
     */
    public function getConfigs(): ConfigCollection
    {
        return $this->configs;
    }

    /**
     * Return a requested config
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->configs->get($key, $default);
    }
}
