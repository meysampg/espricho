<?php

/**
 * fire composer autoloader!
 */
require_once __DIR__ . "{$ds}Bootstraper.php";

use Espricho\Components\Http\Providers\HttpKernelProvider;

/**
 * Register Http kernel as the main kernel of application
 */
$app->registerServiceProvider(HttpKernelProvider::class);
