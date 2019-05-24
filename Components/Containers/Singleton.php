<?php

namespace Espricho\Components\Containers;

use function func_get_args;
use function is_null;

class Singleton
{
    /**
     * The FQN of a class which must be loaded as a singleton
     *
     * @var string
     */
    protected static $class;

    /**
     * The class container
     *
     * @var object
     */
    protected static $instance;

    /**
     * Get an instance of given object
     *
     * @return object
     */
    public static function getInstance(): object
    {
        if (is_null(static::$instance)) {
            static::$instance = new static::$class(...func_get_args());
        }

        return static::$instance;
    }

    /**
     * A shorthand to the getInstance method
     *
     * @return object
     */
    public static function i(): object
    {
        return static::getInstance(...func_get_args());
    }

    /**
     * Singleton constructor.
     */
    private function __construct()
    {
    }
}
