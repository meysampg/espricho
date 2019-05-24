<?php

use Espricho\Components\Singletons\Application;

/**
 * Put all singletons on this file as a helper to access to them with a global
 * defined function
 */

if (!function_exists('app')) {
    /**
     * Return an instance of application
     *
     * @return object
     */
    function app()
    {
        return Application::getInstance();
    }
}
