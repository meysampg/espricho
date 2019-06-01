<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

/**
 * Class ArgumentResolverProvider provides ArgumentResolver service
 *
 * @package Espricho\Components\Http\Providers
 */
class ArgumentResolverProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(ArgumentResolver::class, ArgumentResolver::class);
    }
}
