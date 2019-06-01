<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Routes\Providers\UrlMatcherProvider;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Espricho\Components\Application\Providers\EventDispatcherProvider;

/**
 * Class RouteDispatcherProvider register RouterListener on application
 *
 * @package Espricho\Components\Http\Providers
 */
class RouteDispatcherProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'router_listener_register';

    protected $dependencies = [
         UrlMatcher::class               => UrlMatcherProvider::class,
         RequestStack::class             => RequestStackProvider::class,
         EventDispatcherInterface::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->get(EventDispatcherInterface::class)
               ->addSubscriber(
                    new RouterListener(
                         $system->get(UrlMatcher::class),
                         $system->get(RequestStack::class)
                    )
               )
        ;
    }
}
