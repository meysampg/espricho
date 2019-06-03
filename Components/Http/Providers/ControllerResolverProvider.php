<?php

namespace Espricho\Components\Http\Providers;

use Psr\Log\LoggerInterface;
use Espricho\Components\Application\System;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Application\Providers\LoggerProvider;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * Class ControllerResolverProvider provides ControllerResolver service
 *
 * @package Espricho\Components\Http\Providers
 */
class ControllerResolverProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         LoggerInterface::class => LoggerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(ControllerResolver::class, ControllerResolver::class)
               ->setArguments([new Reference(LoggerInterface::class)])
        ;
    }
}
