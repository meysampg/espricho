<?php

namespace Espricho\Components\Routes\Events;

use Symfony\Component\Routing\Route;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class RouteResolvedEvent extends Event
{
    protected $route;

    protected $request;

    public function __construct(Route $route, Request $request)
    {
        $this->route   = $route;
        $this->request = $request;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}
