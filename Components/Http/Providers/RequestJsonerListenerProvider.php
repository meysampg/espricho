<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Http\Listeners\RequestJsonerListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Espricho\Components\Application\Providers\EventDispatcherProvider;

/**
 * Class RequestJsonerListenerProvider provides RequestJsonerLister
 *
 * @package Espricho\Components\Http\Providers
 */
class RequestJsonerListenerProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'request_jsoner_listener';

    protected $dependencies = [
         EventDispatcherInterface::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->get(EventDispatcherInterface::class)->addSubscriber(new RequestJsonerListener);
    }
}
