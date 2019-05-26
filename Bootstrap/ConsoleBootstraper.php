<?php

use Espricho\Components\Console\Providers\ConsoleProvider;
use Espricho\Components\Console\Providers\HelperSetProvider;
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
 * Register migration commands
 */
$app->registerServiceProvider(MigrationCommandProvider::class);
