<?php

namespace Espricho\Components\Providers;

use Espricho\Components\Application\System;

/**
 * Class AbstractServiceProvider define a provider for service locating
 *
 * @package Espricho\Components\Providers
 */
abstract class AbstractServiceProvider
{
    /**
     * List of services which the provider need them to create its service
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * List of services which the provider can extend its functionality
     *
     * @var array
     */
    protected $suggestions = [];

    /**
     * Load a service on a given app
     *
     * @param System $system
     */
    public function load(System $system)
    {
        $this->loadDependencies($system);
        $this->register($system);
        $this->loadSuggestions($system);
    }

    /**
     * Define service providing rules
     *
     * @param System $system
     *
     * @return mixed
     */
    abstract function register(System $system);

    /**
     * Check a service is loaded or not
     *
     * @param System $system
     * @param string $service
     *
     * @return bool
     */
    protected function isLoaded(System $system, string $service): bool
    {
        return $system->has($service);
    }

    /**
     * Load all dependencies of the service
     *
     * @param System $system
     */
    protected function loadDependencies(System $system)
    {
        foreach ($this->dependencies as $service => $provider) {
            if ($this->isLoaded($system, $service)) {
                continue;
            }

            (new $provider)->load($system);
        }
    }

    /**
     * Load all suggestions of the service
     *
     * @param System $system
     */
    protected function loadSuggestions(System $system)
    {
        foreach ($this->suggestions as $service => $provider) {
            if ($this->isLoaded($system, $service)) {
                continue;
            }

            (new $provider)->load($system);
        }
    }
}
