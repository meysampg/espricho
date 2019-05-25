<?php

use Espricho\Components\Singletons\Application;
use Espricho\Components\Configs\ConfigCollection;
use Espricho\Components\Singletons\EntityManager;

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
     * @param ConfigCollection|null $config
     *
     * @return \Espricho\Components\Databases\EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    function em(ConfigCollection $config = null)
    {
        return EntityManager::getInstance($config);
    }
}
