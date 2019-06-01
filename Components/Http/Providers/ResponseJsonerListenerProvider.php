<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Http\Listeners\ResponseJsonerListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Espricho\Components\Application\Providers\EventDispatcherProvider;

/**
 * Class ResponseJsonerListenerProvider register ResponseJsonerListener listener
 *
 * @package Espricho\Components\Http\Providers
 */
class ResponseJsonerListenerProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'response_jsoner_listener_register';

    protected $dependencies = [
         EventDispatcherInterface::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->get(EventDispatcherInterface::class)->addSubscriber(new ResponseJsonerListener);
    }
}
