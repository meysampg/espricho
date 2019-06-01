<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Espricho\Components\Application\System;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Espricho\Components\Providers\AbstractServiceProvider;

class EntityManagerHelperProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         EntityManagerInterface::class => EntityManagerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(System $system)
    {
        $system->register(EntityManagerHelper::class, EntityManagerHelper::class)
               ->addArgument($system->get(EntityManagerInterface::class))
        ;
    }
}
