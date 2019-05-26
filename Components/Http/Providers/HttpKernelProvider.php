<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Http\HttpKernel;
use Espricho\Components\Application\Application;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Espricho\Components\Application\Providers\EventDispatcherProvider;

/**
 * Class HttpKernelProvider provides HttpKernel service
 *
 * @package Espricho\Components\Http\Providers
 */
class HttpKernelProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         RequestStack::class               => RequestStackProvider::class,
         EventDispatcher::class            => EventDispatcherProvider::class,
         RouteDispatcherProvider::PROVIDE  => RouteDispatcherProvider::class,
         ArgumentResolver::class           => ArgumentResolverProvider::class,
         ControllerResolver::class         => ControllerResolverProvider::class,
         RequestParameterProvider::PROVIDE => RequestParameterProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(KernelInterface::class, HttpKernel::class)
            ->setArguments(
                 [
                      new Reference(EventDispatcher::class),
                      new Reference(ControllerResolver::class),
                      new Reference(RequestStack::class),
                      new Reference(ArgumentResolver::class),
                 ]
            )
        ;
    }
}
