<?php

namespace Espricho\Components\Http\Listeners;

use Espricho\Components\Http\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * Class ResponseJsonerListener convert a raw response to JSON
 *
 * @package Espricho\Components\Http\Listeners
 */
class ResponseJsonerListener implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
             KernelEvents::VIEW => 'jsonize',
        ];
    }

    /**
     * Jsonize the response
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function jsonize(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        $event->setResponse(new JsonResponse($result));
    }
}
