<?php

namespace Espricho\Components\Http\Providers;

use Symfony\Component\HttpFoundation\Request;
use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class RequestParameterProvider provides request parameter
 *
 * @package Espricho\Components\Http\Providers
 */
class RequestParameterProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'request_parameter';

    protected $dependencies = [
         RequestJsonerListenerProvider::PROVIDE => RequestJsonerListenerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $app)
    {
        $app->setParameter(static::PROVIDE, Request::createFromGlobals());
    }
}
