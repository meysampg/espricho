<?php

use Espricho\Components\Contracts\Middleware;
use Espricho\Components\Singletons\Application;
use Espricho\Components\Databases\EntityManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Put all singletons on this file as a helper to access to them with a global
 * defined function
 */

if (!function_exists('app')) {
    /**
     * Return an instance of application
     *
     * @param ParameterBagInterface|null $bag
     *
     * @return \Espricho\Components\Application\Application
     */
    function app(ParameterBagInterface $bag = null)
    {
        return Application::getInstance($bag);
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

if (!function_exists('middleware')) {
    /**
     * Get the class or alias key of a middleware and return its class
     *
     * @param string $key
     *
     * @return Middleware|null
     */
    function middleware(string $key): ?Middleware
    {
        return app()->getMiddleware($key);
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

if (!function_exists('env')) {
    /**
     * Return an environmental variable value
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return null|mixed
     */
    function env(string $key, $default = null)
    {
        $key = strtoupper($key);
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return $default;
    }
}
