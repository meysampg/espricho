<?php

namespace Espricho\Components\Http\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Espricho\Components\Contracts\RequestEvent;
use Espricho\Components\Contracts\RequestResponseEvent;

/**
 * Class AfterHttpKernelFireEvent provides functionality to work woth request
 * and response after a http kernel fire.
 *
 * @package Espricho\Components\Http\Events
 */
class AfterHttpKernelFireEvent extends Event implements RequestResponseEvent
{
    protected $response;
    protected $request;

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): RequestEvent
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): RequestResponseEvent
    {
        $this->response = $response;

        return $this;
    }
}
