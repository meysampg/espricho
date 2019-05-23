<?php

namespace Espricho\Components\Routes;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;

/**
 * Class YamlRouteLoader it loads `routes.yaml` on given directory
 *
 * @package Espricho\Components\Application
 */
class YamlRouteLoader
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * YamlRouteLoader constructor.
     *
     * @param string      $dir
     * @param string|null $route
     */
    public function __construct(string $dir, ?string $route = null)
    {
        $fileLocator = new FileLocator([$dir]);
        $loader      = new YamlFileLoader($fileLocator);
        $route       = $route ?? 'routes.yaml';

        $this->routes = $loader->load($route);
    }

    /**
     * Return a route collection
     *
     * @return RouteCollection
     */
    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }
}
