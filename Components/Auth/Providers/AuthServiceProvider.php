<?php

namespace Espricho\Components\Auth\Providers;

use Espricho\Components\Auth\Auth;
use Espricho\Components\Application\System;
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
        $system->register(Auth::class, Auth::class);
        $system->setAlias('auth', Auth::class);
    }
}
