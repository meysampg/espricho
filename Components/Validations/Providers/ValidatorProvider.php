<?php

namespace Espricho\Components\Validations\Providers;

use Espricho\Components\Application\System;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Espricho\Components\Providers\AbstractServiceProvider;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidatorProvider provides validation service
 *
 * @package Espricho\Components\Validations\Providers
 */
class ValidatorProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register('validator.builder', ValidatorBuilder::class);

        $system->register(ValidatorInterface::class)
               ->setFactory([new Reference('validator.builder'), 'getValidator'])
               ->setPublic(true)
        ;
    }
}
