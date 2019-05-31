<?php

namespace Espricho\Bootstrap;

use Espricho\Components\Application\Bootstraper;
use Espricho\Components\Http\Providers\HttpKernelProvider;

/**
 * Class HttpBootstraper bootstrap HTTP application
 */
class HttpBootstraper extends Bootstraper
{
    /**
     * @inheritdoc
     */
    public function extensions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function serviceProviders(): array
    {
        return [
             HttpKernelProvider::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootParameters(): array
    {
        return [];
    }
}
