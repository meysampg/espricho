<?php

namespace Espricho\Components\Http\Providers;

use Espricho\Components\Application\System;
use Espricho\Components\Http\ContainerResolver;
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
        $system->register(ArgumentResolver::class, ArgumentResolver::class)
               ->setArguments(
                    [
                         null,
                         [
                              new ArgumentResolver\RequestAttributeValueResolver(),
                              new ArgumentResolver\RequestValueResolver(),
                              new ArgumentResolver\SessionValueResolver(),
                              new ArgumentResolver\DefaultValueResolver(),
                              new ArgumentResolver\VariadicValueResolver(),
                              new ContainerResolver(),
                         ],
                    ]
               )
        ;
    }
}
