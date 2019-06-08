<?php

namespace Espricho\Components\Routes\Subscribers;

use Espricho\Components\Application\Onion;
use Espricho\Components\Contracts\Middleware;
use Espricho\Components\Contracts\HttpKernelEvent;
use Espricho\Components\Routes\Events\RouteResolvedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Espricho\Components\Http\Exceptions\InvalidMiddlewareClassException;

use function sprintf;
use function is_object;
use function class_exists;
use function is_subclass_of;

/**
 * Class UrlMatchedMiddlewareSubscriber provides event of route matching
 *
 * @package Espricho\Components\Routes\Subscribers
 */
class UrlMatchedMiddlewareSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
             HttpKernelEvent::ROUTE_MATCHED => 'runMiddleware',
        ];
    }

    /**
     * Run middlewares of route
     *
     * @param RouteResolvedEvent $event
     *
     * @throws InvalidMiddlewareClassException
     */
    public function runMiddleware(RouteResolvedEvent $event)
    {
        $route       = $event->getRoute();
        $middlewares = (array)$route->getDefault('middleware');
        $toRun       = [];

        foreach ($middlewares as $middleware) {
            if (class_exists($middleware)) {
                if (!is_subclass_of($middleware, Middleware::class)) {
                    throw new InvalidMiddlewareClassException(sprintf("%s is not a valid middleware class. Make sure it implements %s.", $middleware, Middleware::class));
                }

                $toRun[] = $this->getMiddleware($middleware);

                continue;
            }

            if (!middleware($middleware)) {
                throw new InvalidMiddlewareClassException(sprintf("%s is not a valid middleware class.", $middleware, Middleware::class));
            }

            $toRun[] = $this->getMiddleware($middleware);
        }

        Onion::run(
             $toRun,
             $event->getRequest()
        );
    }

    /**
     * Ensure to get the middleware class
     *
     * @param $middleware
     *
     * @return Middleware
     */
    private function getMiddleware($middleware): Middleware
    {
        if (is_object($middleware)) {
            return $middleware;
        }

        return new $middleware;
    }
}
