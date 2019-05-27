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

if (!function_exists('service')) {
    /**
     * Return a requested service from the container
     *
     * @param string $key
     *
     * @return object
     */
    function service(string $key)
    {
        return app()->get($key);
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

if (!function_exists('hash_string')) {
    /**
     * Hash a given string with PASSWORD_BCRYPT algorithm
     *
     * @param string $s
     *
     * @return string
     */
    function hash_string(string $s): string
    {
        return password_hash($s, PASSWORD_BCRYPT);
    }
}

if (!function_exists('check_hash')) {
    /**
     * Check a given string matches a hash or not
     *
     * @param string $s
     * @param string $hash
     *
     * @return bool
     */
    function check_hash(string $s, string $hash): bool
    {
        return password_verify($s, $hash);
    }
}
