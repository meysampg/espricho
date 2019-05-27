<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Http\Listeners\RequestJsonerListener;
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
         EventDispatcher::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get(EventDispatcher::class)->addSubscriber(new RequestJsonerListener);
    }
}
