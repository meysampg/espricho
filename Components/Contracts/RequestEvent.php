<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RequestEvent define a Request event signature
 *
 * @package Espricho\Components\Contracts
 */
interface RequestEvent
{
    public function getRequest(): Request;

    public function setRequest(Request $request): RequestEvent;
}
