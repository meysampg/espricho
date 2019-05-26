<?php

namespace Espricho\Components\Http\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AfterHttpKernelFireEvent provides functionality to work woth request
 * and response after a http kernel fire.
 *
 * @package Espricho\Components\Http\Events
 */
class AfterHttpKernelFireEvent
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

    public function setRequest(Request $request): AfterHttpKernelFireEvent
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): AfterHttpKernelFireEvent
    {
        $this->response = $response;

        return $this;
    }
}
