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
     * @param array  $default
     *
     * @return array
     */
    public function get(string $key, $default = null): array
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * Check a config key is present or not
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
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
}
