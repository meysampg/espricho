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
    /**
     * Events which will be raise before of HTTP kernel fire. All subscribers
     * receive a BeforeHttpKernelFireEvent event.
     */
    const EVENT_HTTP_KERNEL_BEFORE_FIRE = 'event_http_kernel_before_fire';

    /**
     * Events which will be raise after of HTTP kernel fire. All subscribers
     * receive a AfterHttpKernelFireEvent event.
     */
    const EVENT_HTTP_KERNEL_AFTER_FIRE = 'event_http_kernel_before_fire';
}
