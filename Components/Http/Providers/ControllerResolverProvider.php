<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\Application;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * Class ControllerResolverProvider provides ControllerResolver service
 *
 * @package Espricho\Components\Http\Providers
 */
class ControllerResolverProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(ControllerResolver::class, ControllerResolver::class);
    }
}
