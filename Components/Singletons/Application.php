<?php

namespace Espricho\Components\Singletons;

use Espricho\Components\Containers\Singleton;
use Espricho\Components\Application\Application as BaseApplication;

/**
 * Class Application is a singleton of our core
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
