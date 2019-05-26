<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\Application;
use Espricho\Components\Contracts\KernelInterface;
use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand;
use Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand;
use Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand;

/**
 * Class OrmCommandProvider provides ORM commands
 *
 * @package Espricho\Components\Databases\Providers
 */
class OrmCommandProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get(KernelInterface::class)->add(new GenerateEntitiesCommand);
        $app->get(KernelInterface::class)->add(new GenerateRepositoriesCommand);
        $app->get(KernelInterface::class)->add(new MappingDescribeCommand);
        $app->get(KernelInterface::class)->add(new ValidateSchemaCommand);
        $app->get(KernelInterface::class)->add(new InfoCommand);
    }
}
