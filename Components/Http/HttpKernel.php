<?php

namespace Espricho\Components\Http;

use Espricho\Components\Application\Onion;
use Symfony\Component\HttpFoundation\Request;
use Espricho\Components\Contracts\HttpKernelEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Contracts\HttpKernelInterface;
use Espricho\Components\Http\Events\AfterHttpKernelFireEvent;
use Espricho\Components\Http\Events\BeforeHttpKernelFireEvent;
use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
use Espricho\Components\Http\Providers\RequestParameterProvider;

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
             ->dispatch(HttpKernelEvent::BEFORE_FIRE, new BeforeHttpKernelFireEvent($request))
        ;

        $response = Onion::run(
             app()->getMiddlewares(),
             $request,
             function (Request $request) {
                 return $this->handle($request)->send();
             }
        );

        app()->get(EventDispatcher::class)
             ->dispatch(HttpKernelEvent::AFTER_FIRE, new AfterHttpKernelFireEvent($request, $response))
        ;

        return $response;
    }
}
