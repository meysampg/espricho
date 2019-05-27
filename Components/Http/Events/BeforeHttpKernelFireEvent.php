<?php

namespace Espricho\Components\Http\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Espricho\Components\Contracts\RequestEvent;

/**
 * Class BeforeHttpKernelFireEvent provides functionality to work with request
 * before take it on the core.
 *
 * @package Espricho\Components\Http\Events
 */
class BeforeHttpKernelFireEvent extends Event implements RequestEvent
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
}
