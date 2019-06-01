<?php

namespace Espricho\Components\Configs;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use Espricho\Components\Helpers\Arr;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Config\Resource\ResourceInterface;
use Espricho\Components\Contracts\ConfigManagerInterface;

use function count;
use function current;
use function explode;
use function is_array;
use function strtolower;

/**
 * Class ConfigCollection provides functions to working with configurations
 *
 * @package Espricho\Components\Configs
 */
class ConfigCollection implements IteratorAggregate, Countable, ConfigManagerInterface
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
     * @var Configuration[]
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
     * Merge a configuration with an already exists
     *
     * @param string        $name
     * @param Configuration $configuration
     */
    public function merge(string $name, Configuration $configuration)
    {
        $this->configs[$name]->merge($name, $configuration);
    }

    /**
     * Merge or add a given configuration
     *
     * @param string        $name
     * @param Configuration $configuration
     */
    public function mergeOrAdd(string $name, Configuration $configuration)
    {
        if ($this->has($name)) {
            $this->merge($name, $configuration);

            return;
        }

        $this->add($name, $configuration);
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
     * @param mixed  $defaults
     * @param string $del
     *
     * @return mixed
     */
    public function get(string $name, $defaults = [], string $del = '.')
    {
        $configs = $this->configs;
        $nameArr = explode($del, $name);
        $key     = current($nameArr);

        if ($this->has($key)) {
            if (is_array($defaults) && !empty($defaults)) {
                $options = new OptionsResolver();
                $options->setDefaults($defaults);

                $configs = $options->resolve($this->all());
            }

            if (count($nameArr) == 1) {
                return $configs[$key];
            }

            return $configs[$key]->get(Arr::ImplodeByPosition($nameArr, $del, 1), $defaults, $del);
        }

        return $defaults;
    }

    /**
     * Check a Configuration is present or not
     *
     * @param string $name
     * @param string $dot
     *
     * @return bool
     */
    public function has(string $name, string $dot = '.'): bool
    {
        $name = explode($dot, $name);
        $key  = current($name);

        return isset($this->configs[$key]) ||
             (
                  count($name) > 1
                  && isset($this->configs[$key])
                  && $this->configs[$key]->has(Arr::ImplodeByPosition($name, $dot, 1))
             );
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

    /**
     * @inheritdoc
     */
    public function addRaw(string $key, $value): void
    {
        $keys = explode('_', strtolower($key));

        $collectionKey = current($keys);
        $configKey     = Arr::implodeByPosition($keys, '_', 1);

        $this->mergeOrAdd($collectionKey, Configuration::addRaw($configKey, $value));
    }
}
