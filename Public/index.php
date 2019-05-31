<?php
/**
 * Load autoloader
 */
$ds   = DIRECTORY_SEPARATOR;
$root = __DIR__ . "{$ds}..";
require_once "{$root}{$ds}vendor{$ds}autoload.php";

use Espricho\Bootstrap\HttpBootstraper;

/**
 * Get the HTTP boostraper...
 */
$bootstraper = new HttpBootstraper($root);

/**
 * And GO ON!
 */
return $bootstraper->getApp()->fire();
