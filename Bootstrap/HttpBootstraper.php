<?php

/**
 * fire composer autoloader!
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Espricho\Components\Http\HttpKernel;
use Espricho\Components\Routes\RoutesLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Espricho\Components\Singletons\Application;
use Espricho\Components\Configs\ConfigCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Espricho\Components\Contracts\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Configs\ConfigurationsLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * The directory separator
 *
 * @var string
 */
$ds = DIRECTORY_SEPARATOR;

/**
 * load application configurations
 *
 * @var $configs ConfigCollection
 */
$configs = (new ConfigurationsLoader(
     __DIR__ . "{$ds}..{$ds}Configs",
     ['app.yaml', 'db.yaml', 'modules.yaml']
))->load();

/**
 * Create the application!
 */
$app = Application::i($configs);

/**
 * fetch routes
 */
$app->setParameter(
     'routes',
     (new RoutesLoader(
          __DIR__ . "{$ds}..{$ds}Configs",
          'routes.yaml',
          $configs
     ))->load()
);

/**
 * Capture the global request
 */
$app->setParameter('request', Request::createFromGlobals());

/**
 * Register request context and set the captured global request as its content
 */
$app->register('context', RequestContext::class);

/**
 * Register kernel dependencies
 */
$app->register('matcher', UrlMatcher::class)
    ->setArguments(['%routes%', new Reference('context')])
;
$app->register('request_stack', RequestStack::class);
$app->register('controller_resolver', ControllerResolver::class);
$app->register('argument_resolver', ArgumentResolver::class);

/**
 * Register event dispatcher
 */
$app->register('dispatcher', EventDispatcher::class);

/**
 * Add router listener to dispatch the coming request to matcher
 */
$app->get('dispatcher')->addSubscriber(new RouterListener($app->get('matcher'), $app->get('request_stack')));

/**
 * Define HTTP kernel dependencies
 */
$app->register('http_kernel', HttpKernel::class)
    ->setArguments(
         [
              new Reference('dispatcher'),
              new Reference('controller_resolver'),
              new Reference('request_stack'),
              new Reference('argument_resolver'),
         ]
    )
;

/**
 * Register Http kernel as the main kernel of application
 */
$app->setAlias(KernelInterface::class, 'http_kernel');

