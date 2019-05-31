<?php

namespace Espricho\Components\Singletons;

use Symfony\Component\Dotenv\Dotenv;
use Espricho\Components\Containers\Singleton;

/**
 * Class EnvLoader provides dot env configurations
 *
 * @package Espricho\Components\Singletons
 */
class EnvLoader extends Singleton
{
    protected static $class = Dotenv::class;
}
