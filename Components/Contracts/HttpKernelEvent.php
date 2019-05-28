<?php

namespace Espricho\Components\Contracts;

/**
 * Interface HttpKernelEvent defines HTTP kernel events
 *
 * @package Espricho\Components\Contracts
 */
interface HttpKernelEvent
{

    /**
     * Events which will be raise before of HTTP kernel fire. All subscribers
     * receive a BeforeHttpKernelFireEvent event.
     */
    const BEFORE_FIRE = 'event_http_kernel_before_fire';

    /**
     * Events which will be raise after of HTTP kernel fire. All subscribers
     * receive a AfterHttpKernelFireEvent event.
     */
    const AFTER_FIRE = 'event_http_kernel_before_fire';

    const ROUTE_MATCHED = 'event_http_kernel_route_matched';
}
