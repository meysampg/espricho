<?php

namespace Espricho\Components\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
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
        $request = Request::createFromGlobals();

        // TODO: add kernel level before middleware support
        $response = $this->handle($request)->send();
        // TODO: add kernel level after middleware support

        return $response;
    }
}
