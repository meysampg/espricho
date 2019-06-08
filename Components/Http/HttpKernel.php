<?php

namespace Espricho\Components\Http;

use Exception;
use Espricho\Components\Application\Onion;
use Symfony\Component\HttpFoundation\Request;
use Espricho\Components\Contracts\HttpKernelEvent;
use Espricho\Components\Contracts\HttpKernelInterface;
use Espricho\Components\Http\Events\AfterHttpKernelFireEvent;
use Espricho\Components\Http\Events\BeforeHttpKernelFireEvent;
use Symfony\Component\HttpKernel\HttpKernel as BaseHttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function sys;

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
        // TODO: use event listener on exceptions
        try {
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
        } catch (Exception $e) {
            $json = ['error' => $e->getMessage()];

            if (sys()->isDevMode()) {
                $json['dev']['file']  = $e->getFile();
                $json['dev']['line']  = $e->getLine();
                $json['dev']['trace'] = $e->getTrace();
            }

            $json = new JsonResponse($json, 500);

            return $json->send();
        }
    }
}
