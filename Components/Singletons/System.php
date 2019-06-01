<?php

namespace Espricho\Components\Singletons;

use Espricho\Components\Containers\Singleton;
use Espricho\Components\Application\System as BaseApplication;

/**
 * Class System is a singleton of our core
 *
 * @package Espricho\Components\Singletons
 */
class Application extends Singleton
{
    /**
     * @inheritdoc
     */
    protected static $class = BaseApplication::class;
}
