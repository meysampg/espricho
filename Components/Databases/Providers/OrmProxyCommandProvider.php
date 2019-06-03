<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Providers\AbstractServiceProvider;
use Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand;

/**
 * Class OrmProxyCommandProvider add generate proxy command to the console
 *
 * @package Espricho\Components\Databases\Providers
 */
class OrmProxyCommandProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(System $system)
    {
        $system->get(KernelInterface::class)->add(new GenerateProxiesCommand);
    }
}
