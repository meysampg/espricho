<?php

use Espricho\Components\Console\Console;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;

/**
 * fire composer autoloader!
 */
require_once __DIR__ . "{$ds}Bootstraper.php";

$app = new Console("Espricho", "0.1");
$app->setConfigs($configs);

$em        = em($configs);
$helperSet = new HelperSet(["db" => new ConnectionHelper($em->getConnection())]);
$app->setHelperSet($helperSet);

$app->add(new GenerateCommand());
