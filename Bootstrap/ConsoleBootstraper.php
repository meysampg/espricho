<?php

use Espricho\Components\Console\Providers\ConsoleProvider;
use Espricho\Components\Console\Providers\HelperSetProvider;
use Espricho\Components\Databases\Providers\OrmCommandProvider;
use Espricho\Components\Databases\Providers\OrmCacheCommandProvider;
use Espricho\Components\Databases\Providers\MigrationCommandProvider;

/**
 * fire composer autoloader!
 */
require_once __DIR__ . "{$ds}Bootstraper.php";

/**
 * Register console kernel provider
 */
$app->registerServiceProvider(ConsoleProvider::class);

/**
 * Register helper set of console
 */
$app->registerServiceProvider(HelperSetProvider::class);

/**
 * Register ORM generator commands
 */
$app->registerServiceProvider(OrmCommandProvider::class);

/**
 * Register ORM cache clear commands
 */
$app->registerServiceProvider(OrmCacheCommandProvider::class);

/**
 * Register migration commands
 */
$app->registerServiceProvider(MigrationCommandProvider::class);
