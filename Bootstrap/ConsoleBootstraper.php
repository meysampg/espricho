<?php

use Espricho\Components\Console\Console;
use Symfony\Component\Console\Helper\HelperSet;
use Espricho\Components\Contracts\KernelInterface;
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

$app->register(KernelInterface::class, Console::class)
    ->setArguments(["Espricho", "0.1"])
;

/**
 * Load entity manager and assign the helper set
 */
$em        = em($configs);
$helperSet = new HelperSet(["db" => new ConnectionHelper($em->getConnection())]);
$app->get('console_kernel')->setHelperSet($helperSet);

/**
 * Register console kernel as the application kernel
 */
$app->setAlias(KernelInterface::class, 'console_kernel');

/**
 * Register migration commands
 */
$app->get('console_kernel')->add(new GenerateCommand());
$app->get('console_kernel')->add(new DiffCommand());
$app->get('console_kernel')->add(new MigrateCommand());
$app->get('console_kernel')->add(new RollupCommand());
$app->get('console_kernel')->add(new StatusCommand());
$app->get('console_kernel')->add(new DumpSchemaCommand());
