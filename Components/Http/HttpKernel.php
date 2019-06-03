<?php

namespace Espricho\Components\Http;

use Espricho\Components\Application\Onion;
use Symfony\Component\HttpFoundation\Request;
use Espricho\Components\Contracts\HttpKernelEvent;
use Espricho\Components\Contracts\HttpKernelInterface;
use Espricho\Components\Http\Events\AfterHttpKernelFireEvent;
use Espricho\Components\Http\Events\BeforeHttpKernelFireEvent;
use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
        $request = Request::createFromGlobals();

        sys()->get(EventDispatcherInterface::class)
             ->dispatch(HttpKernelEvent::BEFORE_FIRE, new BeforeHttpKernelFireEvent($request))
        ;

        $response = Onion::run(
             sys()->getMiddlewares(),
             $request,
             function (Request $request) {
                 return $this->handle($request)->send();
             }
        );

        sys()->get(EventDispatcherInterface::class)
             ->dispatch(HttpKernelEvent::AFTER_FIRE, new AfterHttpKernelFireEvent($request, $response))
        ;

        return $response;
    }
}
