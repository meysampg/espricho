<?php

namespace Espricho\Components\Singletons;

use Espricho\Components\Containers\Singleton;
use Espricho\Components\Application\System as BaseSystem;

/**
 * Class System is a singleton of our core
 *
 * @package Espricho\Components\Singletons
 */
class System extends Singleton
{
    /**
     * @inheritdoc
     */
    protected static $class = BaseSystem::class;
}
