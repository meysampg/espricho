<?php

/**
 * Get the HTTP boostraper...
 */
$ds  = DIRECTORY_SEPARATOR;
require_once __DIR__ . "{$ds}..{$ds}Bootstrap{$ds}HttpBootstraper.php";

/**
 * And GO ON!
 */
return $app->fire();
