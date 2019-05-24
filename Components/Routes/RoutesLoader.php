<?php

namespace Espricho\Components\Routes;

use Espricho\Components\Application\Module;
use Symfony\Component\Routing\RouteCollection;
use Espricho\Components\Configs\ConfigCollection;

use function ucfirst;

/**
 * Class RoutesLoader load all routes of application
 *
 * @package Espricho\Components\Routes
 */
class RoutesLoader
{
    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var ConfigCollection
     */
    protected $configs;

    /**
     * RoutesLoader constructor.
     *
     * @param string           $dir
     * @param string           $file
     * @param ConfigCollection $configs
     */
    public function __construct(
         string $dir,
         string $file,
         ?ConfigCollection $configs = null
    ) {
        $this->dir     = $dir;
        $this->file    = $file;
        $this->configs = $configs;
    }

    /**
     * Load routes
     *
     * @return RouteCollection
     */
    public function load(): RouteCollection
    {
        if (!is_null($this->routes)) {
            return $this->routes;
        }

        $this->routes = new RouteCollection();

        $yamlLoader = new YamlRouteLoader($this->dir, $this->file);
        $this->routes->addCollection($yamlLoader->getRoutes());

        if ($this->configs && $this->configs->has('modules')) {
            $this->loadModulesRoutes();
        }

        return $this->routes;
    }

    /**
     * Load modules routes
     */
    protected function loadModulesRoutes()
    {
        foreach ($this->configs->get('modules') as $name => $config) {
            /**
             * On an HMVC manner, a module can have a submodule, so we can call
             * this loading process on a recursive fashion.
             */
            $ds = DIRECTORY_SEPARATOR;
            $this->loadModuleRoutes($this->routes, $config->all(), $name, __DIR__ . "{$ds}..{$ds}..{$ds}Modules");
        }
    }

    /**
     * Load routes of a module and its submodules if exists
     *
     * @param RouteCollection $routes The set of routes collection
     * @param array           $module The module information
     * @param string          $name   Name of module on the configurations
     * @param null|string     $dir    Directory contains routes configurations
     * @param null|string     $file   Routes file name
     */
    protected function loadModuleRoutes(
         RouteCollection $routes,
         array $module,
         string $name,
         ?string $dir = null,
         ?string $file = null
    ) {
        $module = Module::infoNormalizer($name, $module);
        $ds     = DIRECTORY_SEPARATOR;
        $name   = ucfirst($name);
        $dir    = ($dir ?? $this->dir) . "{$ds}{$name}";
        $file   = $file ?? $this->file;

        $yamlLoader = new YamlRouteLoader($dir . "{$ds}Configs", $file);
        $routes->addCollection($yamlLoader->getRoutes());

        if (isset($module["modules"])) {
            $dir .= "{$ds}Modules";

            foreach ($module["modules"] as $name => $subModule) {
                $this->loadModuleRoutes(
                     $routes,
                     $subModule,
                     $name,
                     $dir,
                     $file
                );
            }
        }
    }
}
