<?php

namespace Espricho\Components\Http;

use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
use Espricho\Components\Http\Providers\RequestParameterProvider;
use Espricho\Components\Contracts\HttpKernelInterfaceInterface as HttpKernelInterface;

/**
 * Class HttpKernelInterfaceInterface provides the Http kernel for the
 * application
 *
 * @package Espricho\Components\Http
 */
class HttpKernel extends BaseHttpKernel implements HttpKernelInterface
{
    public function fire()
    {
        // TODO: add kernel level before middleware support
        $response = $this->handle(app()->getParameter(RequestParameterProvider::PROVIDE))->send();
        // TODO: add kernel level after middleware support

        return $response;
    }
}
