<?php

namespace Espricho\Components\Configs;

use Serializable;

use function serialize;
use function unserialize;

/**
 * Class Configuration provides working with a configuration functionality
 *
 * @package Espricho\Components\Configs
 */
class Configuration implements Serializable
{
    /**
     * Configuration holder
     *
     * @var array
     */
    protected $data = [];

    /**
     * Configuration constructor.
     *
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        $this->data = (array)$data;
    }

    /**
     * Serialize the configuration
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * Unmarshal the configuration
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }

    /**
     * Return the data set of configuration
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Return a Configuration value of a given key
     *
     * @param string $key
     * @param mixed  $default
     * @param string $del
     *
     * @return mixed
     */
    public function get(string $key, $default = null, string $del = '.')
    {
        return $this->dotFinder($key, $this->data, $del) ?? $default;
    }

    /**
     * Check a config key is present or not
     *
     * @param string $key
     * @param string $del
     *
     * @return bool
     */
    public function has(string $key, string $del = '.'): bool
    {
        return $this->dotFinder($key, $this->data, $del) !== null;
    }

    /**
     * Add a config
     *
     * @param string $key
     * @param mixed  $value
     */
    public function add(string $key, $value)
    {
        $this->remove($key);

        $this->data[$key] = $value;
    }

    /**
     * Remove a key from configuration
     *
     * @param string $key
     */
    public function remove(string $key)
    {
        unset($this->data[$key]);
    }

    /**
     * Search a configuration for a given dot-notation configuration key
     *
     * @param string $name
     * @param array  $config
     * @param string $dot
     *
     * @return mixed
     */
    protected function dotFinder(string $name, array $config, string $dot = '.')
    {
        $name = explode($dot, $name);

        foreach ($name as $key) {
            if (!isset($config[$key])) {
                return null;
            }

            $config = $config[$key];
        }

        return $config;
    }
}
