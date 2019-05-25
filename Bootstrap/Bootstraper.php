<?php
/**
 * The directory separator
 *
 * @var string
 */
$ds = DIRECTORY_SEPARATOR;

/**
 * fire composer autoloader!
 */
require_once __DIR__ . "{$ds}..{$ds}vendor{$ds}autoload.php";

use Espricho\Components\Configs\ConfigCollection;
use Espricho\Components\Configs\ConfigurationsLoader;

/**
 * load application configurations
 *
 * @var $configs ConfigCollection
 */
$configs = (new ConfigurationsLoader(
     __DIR__ . "{$ds}..{$ds}Configs",
     ['app.yaml', 'db.yaml', 'modules.yaml']
))->load();
