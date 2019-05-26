<?php

namespace Espricho\Components\Http\Events;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class BeforeHttpKernelFireEvent provides functionality to work with request
 * before take it on the core.
 *
 * @package Espricho\Components\Http\Events
 */
class BeforeHttpKernelFireEvent
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

    public function setRequest(Request $request): BeforeHttpKernelFireEvent
    {
        $this->request = $request;

        return $this;
    }
}
