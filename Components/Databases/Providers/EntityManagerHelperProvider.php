<?php

namespace Espricho\Components\Databases\Providers;

use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Espricho\Components\Application\Application;
use Espricho\Components\Databases\EntityManager;
use Espricho\Components\Providers\AbstractServiceProvider;

class EntityManagerHelperProvider extends AbstractServiceProvider
{
    protected $dependencies = [
         EntityManager::class => EntityManagerProvider::class,
    ];

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $app->register(EntityManagerHelper::class, EntityManagerHelper::class)
            ->addArgument($app->get(EntityManager::class))
        ;
    }
}
