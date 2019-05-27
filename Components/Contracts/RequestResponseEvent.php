<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface RequestResponseEvent define events contain both request and response
 *
 * @package Espricho\Components\Contracts
 */
interface RequestResponseEvent extends RequestEvent
{
    public function getResponse(): Response;

    public function setResponse(Response $response): RequestResponseEvent;
}
