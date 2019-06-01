<?php

namespace Espricho\Components\Console\Providers;

use Espricho\Components\Console\Console;
use Espricho\Components\Application\System;
use Espricho\Components\Contracts\KernelInterface;
use Espricho\Components\Providers\AbstractServiceProvider;

/**
 * Class ConsoleProvider register console kernel
 *
 * @package Espricho\Components\Console\Providers
 */
class ConsoleProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(KernelInterface::class, Console::class)
               ->setArguments(
                    [
                         $system->getConfig('app.name', 'Espricho'),
                         $system->getConfig('app.version', '0.1'),
                    ]
               )
        ;
    }
}
