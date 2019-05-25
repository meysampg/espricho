<?php

use Espricho\Components\Console\Console;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;

/**
 * fire composer autoloader!
 */
require_once __DIR__ . "{$ds}Bootstraper.php";

/**
 * Create console application
 */
$app = new Console("Espricho", "0.1");
$app->setConfigs($configs);

/**
 * Load entity manager and assign the helper set
 */
$em        = em($configs);
$helperSet = new HelperSet(["db" => new ConnectionHelper($em->getConnection())]);
$app->setHelperSet($helperSet);

/**
 * Register migration commands
 */
$app->add(new GenerateCommand());
$app->add(new DiffCommand());
$app->add(new MigrateCommand());
$app->add(new RollupCommand());
$app->add(new StatusCommand());
$app->add(new DumpSchemaCommand());
