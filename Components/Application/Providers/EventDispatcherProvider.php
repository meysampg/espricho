<?php

namespace Espricho\Components\Application\Providers;

use Espricho\Components\Application\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class EventDispatcherProvider provides EventDispatcher service
 *
 * @package Espricho\Components\Application\Providers
 */
class EventDispatcherProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(EventDispatcher::class, EventDispatcher::class);
    }
}
