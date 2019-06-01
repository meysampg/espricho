<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Routes\Providers\UrlMatcherProvider;
use Symfony\Component\HttpKernel\EventListener\RouterListener;

/**
 * Class RouteDispatcherProvider register RouterListener on application
 *
 * @package Espricho\Components\Http\Providers
 */
class RouteDispatcherProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'router_listener_register';

    protected $dependencies = [
         UrlMatcher::class   => UrlMatcherProvider::class,
         RequestStack::class => RequestStackProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $app)
    {
        $app->get(EventDispatcher::class)
            ->addSubscriber(
                 new RouterListener(
                      $app->get(UrlMatcher::class),
                      $app->get(RequestStack::class)
                 )
            )
        ;
    }
}
