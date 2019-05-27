<?php

namespace Espricho\Components\Databases\Providers;

use Espricho\Components\Application\Application;
use Espricho\Components\Providers\AbstractServiceProvider;
use Espricho\Components\Databases\Listeners\ModelUpdatedListener;

/**
 * Class ModelUpdatedListenerProvider
 *
 * @package Espricho\Components\Databases\Providers
 */
class ModelUpdatedSubscriberProvider extends AbstractServiceProvider
{
    public const PROVIDE = 'model_updated_listener';

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        em()->getEventManager()->addEventSubscriber(new ModelUpdatedListener);
    }
}
