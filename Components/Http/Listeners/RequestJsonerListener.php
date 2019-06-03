<?php

namespace Espricho\Components\Http\Listeners;

use Espricho\Components\Contracts\RequestEvent;
use Espricho\Components\Contracts\HttpKernelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use function stripos;
use function json_decode;

/**
 * Class RequestJsonerListener convert a given json request to request class
 *
 * @package Espricho\Components\Http\Listeners
 */
class RequestJsonerListener implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
             HttpKernelEvent::BEFORE_FIRE => 'jsonize',
        ];
    }

    /**
     * Convert a given json request to normal request
     *
     * @param RequestEvent $event
     */
    public function jsonize(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (0 === stripos($request->headers->get('content-type'), 'application/json')) {
            $content = $request->getContent();
            if (!$content) {
                return;
            }

            $content = json_decode($content, true);
            $request->request->replace($content);

            $event->setRequest($request);
        }
    }
}
