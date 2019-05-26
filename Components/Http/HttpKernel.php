<?php

namespace Espricho\Components\Http;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Http\Events\AfterHttpKernelFireEvent;
use Espricho\Components\Http\Events\BeforeHttpKernelFireEvent;
use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
use Espricho\Components\Http\Providers\RequestParameterProvider;
use Espricho\Components\Contracts\HttpKernelInterface as HttpKernelInterface;

/**
 * Class HttpKernelInterface provides the Http kernel for the
 * application
 *
 * @package Espricho\Components\Http
 */
class HttpKernel extends BaseHttpKernel implements HttpKernelInterface
{
    public function fire()
    {
        $request = app()->getParameter(RequestParameterProvider::PROVIDE);

        app()->get(EventDispatcher::class)
             ->dipatch(HttpKernelInterface::EVENT_HTTP_KERNEL_BEFORE_FIRE, new BeforeHttpKernelFireEvent($request))
        ;

        $response = $this->handle($request)->send();

        app()->get(EventDispatcher::class)
             ->dipatch(HttpKernelInterface::EVENT_HTTP_KERNEL_AFTER_FIRE, new AfterHttpKernelFireEvent($request, $response))
        ;

        return $response;
    }
}
