<?php

namespace Espricho\Components\Routes\Providers;

use Espricho\Components\Routes\RoutesLoader;
use Espricho\Components\Application\Application;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class RoutesProvider define routes globally
 *
 * @package Espricho\Components\Routes\Providers
 */
class RoutesProvider extends AbstractServiceProvider
{
    /**
     * Share a key for what this providers sharing
     */
    public const PROVIDE = 'routes_parameter';

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $ds = DIRECTORY_SEPARATOR;
        $app->setParameter(
             static::PROVIDE,
             (new RoutesLoader(
                  __DIR__ . "{$ds}..{$ds}..{$ds}..{$ds}Configs",
                  'routes.yaml',
                  $app->getConfigs()
             ))->load()
        );
    }
}
