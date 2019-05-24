<?php

/**
 * Get the HTTP boostraper...
 */

use Espricho\Components\Singletons\Application;

$ds = DIRECTORY_SEPARATOR;
require_once __DIR__ . "{$ds}..{$ds}Bootstrap{$ds}HttpBootstraper.php";

/**
 * And GO ON!
 */
return Application::i()->fire();
