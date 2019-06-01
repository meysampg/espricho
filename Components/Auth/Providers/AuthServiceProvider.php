<?php

namespace Espricho\Components\Auth\Providers;

use Psr\Log\LoggerInterface;
use Espricho\Components\Auth\Auth;
use Espricho\Components\Application\System;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class AuthServiceProvider provides authentication service
 *
 * @package Espricho\Components\Auth\Providers
 */
class AuthServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(Auth::class, Auth::class)
               ->setArguments([new Reference(LoggerInterface::class)])
               ->setPublic(true)
        ;
        $system->setAlias('auth', Auth::class)->setPublic(true);
    }
}
