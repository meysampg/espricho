<?php

namespace Espricho\Components\Routes\Providers;

use Symfony\Component\Routing\RequestContext;
use Espricho\Components\Application\System;
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
    public function register(System $system)
    {
        $system->register(RequestContext::class, RequestContext::class);
    }
}
