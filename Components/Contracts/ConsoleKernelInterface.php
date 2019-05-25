<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Interface ConsoleKernelInterface defines the console kernel functionality
 *
 * @package Espricho\Components\Contracts
 */
interface ConsoleKernelInterface extends KernelInterface
{
    /**
     * Handle a console command
     */
    public function fire();
}
