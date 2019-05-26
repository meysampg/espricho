<?php

use Espricho\Components\Singletons\Application;
use Espricho\Components\Databases\EntityManager;
use Espricho\Components\Configs\ConfigCollection;

/**
 * Put all singletons on this file as a helper to access to them with a global
 * defined function
 */

if (!function_exists('app')) {
    /**
     * Return an instance of application
     *
     * @param ConfigCollection|null $config
     *
     * @return \Espricho\Components\Application\Application
     */
    function app(ConfigCollection $config = null)
    {
        return Application::getInstance($config);
    }
}

if (!function_exists('em')) {
    /**
     * Return an instance of entity manager
     *
     * @return \Espricho\Components\Databases\EntityManager
     * @throws Exception
     */
    function em()
    {
        return app()->get(EntityManager::class);
    }
}
