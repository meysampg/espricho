<?php

namespace Espricho\Components\Providers;

use Espricho\Components\Application\Application;

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
     * Load a service on a given app
     *
     * @param Application $app
     */
    public function load(Application $app)
    {
        $this->loadDependencies($app);
        $this->register($app);
    }

    /**
     * Define service providing rules
     *
     * @param Application $app
     *
     * @return mixed
     */
    abstract function register(Application $app);

    /**
     * Check a service is loaded or not
     *
     * @param Application $app
     * @param string      $service
     *
     * @return bool
     */
    protected function isLoaded(Application $app, string $service): bool
    {
        return $app->has($service);
    }

    /**
     * Load all dependencies of the service
     *
     * @param Application $app
     */
    protected function loadDependencies(Application $app)
    {
        foreach ($this->dependencies as $service => $provider) {
            if ($this->isLoaded($app, $service)) {
                continue;
            }

            (new $provider)->load($app);
        }
    }
}
