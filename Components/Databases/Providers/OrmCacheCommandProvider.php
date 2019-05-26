<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\Application;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\QueryRegionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\EntityRegionCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\CollectionRegionCommand;

/**
 * Class OrmCacheCommandProvider provides ORM cache clear commands
 *
 * @package Espricho\Components\Databases\Providers
 */
class OrmCacheCommandProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->get(KernelInterface::class)->add(new EntityRegionCommand);
        $app->get(KernelInterface::class)->add(new CollectionRegionCommand);
        $app->get(KernelInterface::class)->add(new MetadataCommand);
        $app->get(KernelInterface::class)->add(new QueryCommand);
        $app->get(KernelInterface::class)->add(new QueryRegionCommand);
        $app->get(KernelInterface::class)->add(new ResultCommand);
    }
}
