<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\HttpKernel\HttpKernelInterface as BaseHttpKernelInterface;

/**
 * Interface HttpKernelInterface provides contracts of a HTTP kernel
 *
 * @package Espricho\Components\Contracts
 */
interface HttpKernelInterface extends BaseHttpKernelInterface, KernelInterface
{
}
