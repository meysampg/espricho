<?php

namespace Espricho\Components\Contracts;

/**
 * Interface KernelInterface provides the kernel of application
 *
 * @package Espricho\Components\Contracts
 */
interface KernelInterface
{
    /**
     * Fire up the application!
     */
    public function fire();
}
