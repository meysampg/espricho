<?php

namespace Espricho\Components\Configs;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Config\Resource\ResourceInterface;

use function count;

/**
 * Class ConfigCollection provides functions to working with configurations
 *
 * @package Espricho\Components\Configs
 */
class ConfigCollection implements IteratorAggregate, Countable
{
    /**
     * Array of resources
     *
     * @var array
     */
    protected $resources = [];

    /**
     * Array of configurations
     *
     * @var array
     */
    protected $configs = [];

    /**
     * Adds a configuration
     *
     * @param string        $name          The configuration name
     * @param Configuration $configuration A Configuration instance
     */
    public function add($name, Configuration $configuration)
    {

        $this->configs[$name] = $configuration;
    }

    /**
     * Return all configurations in the set
     *
     * @return array
     */
    public function all(): array
    {
        return (array)$this->configs;
    }

    /**
     * Return a Configuration
     *
     * @param string $name
     * @param array  $defaults
     *
     * @return array
     */
    public function get(string $name, array $defaults = []): array
    {
        if ($this->has($name)) {
            if (!empty($defaults)) {
                $options = new OptionsResolver();
                $options->setDefaults($defaults);

                return $options->resolve($this->configs[$name]->all());
            }

            return $this->configs[$name]->all();
        }

        return $defaults;
    }

    /**
     * Check a Configuration is present or not
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->configs[$name]);
    }

    /**
     * Remove a configuration
     *
     * @param string $name
     */
    public function remove(string $name)
    {
        unset($this->configs[$name]);
    }

    /**
     * Merge another collection into this
     *
     * @param ConfigCollection $collection
     */
    public function addCollection(ConfigCollection $collection)
    {
        foreach ($collection->all() as $name => $config) {
            $this->add($name, $config);
        }
    }

    /**
     * Adds a resource for this collection. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource
     */
    public function addResource(ResourceInterface $resource)
    {
        $key = (string)$resource;

        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }

    /**
     * Gets the current ConfigCollection as an Iterator that includes all
     * configs.
     * It implements IteratorAggregate.
     *
     * @see all()
     * @return ArrayIterator|Configuration[] An ArrayIterator object for
     *                                       iterating over configs
     */
    public function getIterator()
    {
        return new ArrayIterator($this->configs);
    }

    /**
     * Gets the number of Configurations in this collection.
     *
     * @return int The number of configs
     */
    public function count()
    {
        return count($this->configs);
    }
}
