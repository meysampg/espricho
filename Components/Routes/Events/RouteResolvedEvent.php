<?php

namespace Espricho\Components\Routes\Events;

use Symfony\Component\Routing\Route;
use Symfony\Component\EventDispatcher\Event;

class RouteResolvedEvent extends Event
{
    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
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
}
