<?php

namespace Espricho\Components\Databases\Listeners;

use DateTimeImmutable;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use function method_exists;

/**
 * Class ModelUpdatedListener update time fields of an entity
 *
 * @package Espricho\Components\Databases\Listeners
 */
class ModelUpdatedListener implements EventSubscriber
{
    protected $time;

    public function __construct()
    {
        $this->time = new DateTimeImmutable();
    }

    public function getSubscribedEvents()
    {
        return [
             Events::prePersist,
             Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt($this->time);
        }

        $this->preUpdate($eventArgs);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt($this->time);
        }
    }
}
