<?php

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Espricho\Components\Singletons\System;
use Espricho\Components\Contracts\Middleware;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Put all singletons on this file as a helper to access to them with a global
 * defined function
 */

if (!function_exists('sys')) {
    /**
     * Return an instance of system
     *
     * @param ParameterBagInterface|null $bag
     *
     * @return \Espricho\Components\Application\System
     */
    function sys(ParameterBagInterface $bag = null)
    {
        return System::getInstance($bag);
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
        return sys()->get($key);
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
        return sys()->getMiddleware($key);
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
        return sys()->get(EntityManagerInterface::class);
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

if (!function_exists('logger')) {
    /**
     * Send a given log to the logger service
     *
     * @param string $logType
     * @param mixed $message
     * @param mixed  $context
     */
    function logger(string $logType, $message, $context = [])
    {
        if (!sys()->has(LoggerInterface::class)) {
            return;
        }

        if ($message instanceof Exception) {
            $context = ['exception' => $message];
            $message = '';
        }

        if ($context instanceof Exception) {
            $context = ['exception' => $context];
        }

        service(LoggerInterface::class)->{$logType}($message, $context);
    }
}
