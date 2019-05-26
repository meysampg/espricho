<?php

namespace Espricho\Components\Routes\Providers;

use Symfony\Component\Routing\RequestContext;
use Espricho\Components\Application\Application;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class UrlMatcherProvider provides UrlMatcher service
 *
 * @package Espricho\Components\Routes\Providers
 */
class UrlMatcherProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         RoutesProvider::PROVIDE => RoutesProvider::class,
         RequestContext::class   => RequestContextProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(UrlMatcher::class, UrlMatcher::class)
            ->setArguments([$app->getParameterHolder(RoutesProvider::PROVIDE), new Reference(RequestContext::class)])
        ;
    }
}