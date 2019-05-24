<?php

use Espricho\Components\Singletons\Application;
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
     * @return object
     */
    function app(ConfigCollection $config = null)
    {
        return Application::getInstance($config);
    }
}
