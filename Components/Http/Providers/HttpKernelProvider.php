<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Auth\Auth;
use Espricho\Components\Http\HttpKernel;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Auth\Providers\AuthServiceProvider;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
         RequestStack::class                     => RequestStackProvider::class,
         EventDispatcherInterface::class         => EventDispatcherProvider::class,
         RouteDispatcherProvider::PROVIDE        => RouteDispatcherProvider::class,
         ArgumentResolver::class                 => ArgumentResolverProvider::class,
         ControllerResolver::class               => ControllerResolverProvider::class,
         ResponseJsonerListenerProvider::PROVIDE => ResponseJsonerListenerProvider::class,
         Auth::class                             => AuthServiceProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(KernelInterface::class, HttpKernel::class)
               ->setArguments(
                    [
                         new Reference(EventDispatcherInterface::class),
                         new Reference(ControllerResolver::class),
                         new Reference(RequestStack::class),
                         new Reference(ArgumentResolver::class),
                    ]
               )
               ->setPublic(true)
        ;
    }
}
