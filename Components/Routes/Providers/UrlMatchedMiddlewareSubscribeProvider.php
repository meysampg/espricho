<?php

namespace Espricho\Components\Routes\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Application\Providers\EventDispatcherProvider;
use Espricho\Components\Routes\Subscribers\UrlMatchedMiddlewareSubscriber;

use function service;

/**
 * Class UrlMatchedMiddlewareSubscribeProvider register middle runner on route
 * matching subscriber
 *
 * @package Espricho\Components\Routes\Providers
 */
class UrlMatchedMiddlewareSubscribeProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'url_matched_middleware_subscriber';

    protected $dependencies = [
         EventDispatcher::class => EventDispatcherProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        service(EventDispatcher::class)->addSubscriber(new UrlMatchedMiddlewareSubscriber);
    }
}
