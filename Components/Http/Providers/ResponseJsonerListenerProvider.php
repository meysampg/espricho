<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Http\Listeners\ResponseJsonerListener;
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
         EventDispatcher::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get(EventDispatcher::class)->addSubscriber(new ResponseJsonerListener);
    }
}
