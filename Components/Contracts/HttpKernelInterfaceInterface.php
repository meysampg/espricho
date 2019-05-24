<?php

namespace Espricho\Components\Contracts;

use Symfony\Component\HttpKernel\HttpKernelInterface as BaseHttpKernelInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface HttpKernelInterfaceInterface provides contracts of a HTTP kernel
 *
 * @package Espricho\Components\Contracts
 */
interface HttpKernelInterfaceInterface extends BaseHttpKernelInterface, KernelInterface
{
    /**
     * Fire up the application!
     *
     * @param Request $request
     *
     * @return Response
     */
    public function fire(Request $request);
}
