<?php

namespace Espricho\Controllers\Middlewares;

use Espricho\Components\Contracts\Middleware;
use Espricho\Controllers\Middlewares\Exceptions\InvalidTokenException;
use function ltrim;
use function service;
use function stripos;
use function substr;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthMiddleware checks the current request is from an authenticated user
 * or not.
 *
 * @package Espricho\Controllers\Middlewares
 */
class AuthMiddleware implements Middleware
{
    /**
     * @inheritdoc
     * @throws InvalidTokenException
     */
    public function handle(Request $request, ?Response $response, callable $next)
    {
        if (

             ($token = $request->request->get('token', null)) !== null
             || ($token = $request->headers->get('Authorization', null)) !== null
        ) {
            if (stripos($token, 'bearer:') === 0) {
                $token = ltrim(substr($token, 7));
            }

            if (!service('auth')->validate($token)) {
                throw new InvalidTokenException("Given token is invalid.");
            }
        } else {
            throw new InvalidTokenException("The request is unauthorized.");
        }

        return $next($request, $response);
    }
}
