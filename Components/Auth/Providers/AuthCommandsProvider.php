<?php

namespace Espricho\Components\Auth\Providers;

use Espricho\Components\Auth\Auth;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Auth\Commands\AdminUserCommand;
use Espricho\Components\Auth\Commands\CreateUserCommand;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class AuthCommandsProvider provides authentication commands
 *
 * @package Espricho\Components\Auth\Providers
 */
class AuthCommandsProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         Auth::class => AuthServiceProvider::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->get(KernelInterface::class)->add(new CreateUserCommand);
        $system->get(KernelInterface::class)->add(new AdminUserCommand);
    }
}
