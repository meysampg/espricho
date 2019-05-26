<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\Application;
use Symfony\Component\HttpFoundation\RequestStack;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class RequestStackProvider provides RequestStack service
 *
 * @package Espricho\Components\Http\Providers
 */
class RequestStackProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(RequestStack::class, RequestStack::class);
    }
}
