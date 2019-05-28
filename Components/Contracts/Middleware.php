<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface Middleware defines a middleware rules
 *
 * @package Espricho\Components\Contracts
 */
interface Middleware
{
    /**
     * A middleware main function
     *
     * @param Request  $request
     * @param callable $next The callable must accept a request and a callable
     *                       and the result of the running must be null or a
     *                       Response object
     *
     * @return null|Response
     */
    public function handle(Request $request, callable $next): ?Response;
}
