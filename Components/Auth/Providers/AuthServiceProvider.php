<?php

namespace Espricho\Components\Auth\Providers;

use Espricho\Components\Auth\Auth;
use Espricho\Components\Application\Application;
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
    public function register(Application $app)
    {
        $app->register(Auth::class, Auth::class);
        $app->setAlias('auth', Auth::class);
    }
}
