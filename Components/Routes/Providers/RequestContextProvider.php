<?php

namespace Espricho\Components\Routes\Providers;

use Symfony\Component\Routing\RequestContext;
use Espricho\Components\Application\Application;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class RequestContextProvider provides a RequestContext
 *
 * @package Espricho\Components\Routes\Providers
 */
class RequestContextProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(RequestContext::class, RequestContext::class);
    }
}
