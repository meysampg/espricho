<?php

namespace Espricho\Components\Application\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class EventDispatcherProvider provides EventDispatcher service
 *
 * @package Espricho\Components\System\Providers
 */
class EventDispatcherProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(EventDispatcher::class, EventDispatcher::class);
    }
}
