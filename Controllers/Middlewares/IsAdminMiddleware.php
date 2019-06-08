<?php

namespace Espricho\Controllers\Middlewares;

use Espricho\Components\Contracts\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Espricho\Controllers\Middlewares\Exceptions\UnauthorizedActionException;

/**
 * Class AuthMiddleware checks the current request is from an admin user
 * or not.
 *
 * @package Espricho\Controllers\Middlewares
 */
class IsAdminMiddleware implements Middleware
{
    /**
     * @inheritdoc
     */
    public function handle(Request $request, callable $next): ?Response
    {
        if (!sys()->getUser()) {
            throw new UnauthorizedActionException('You must login on the system.');
        }

        if (!sys()->getUser()->getIsAdmin()) {
            throw new UnauthorizedActionException('You are not authorized to do this action.');
        }

        return $next($request);
    }
}
