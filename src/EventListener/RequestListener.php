<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestListener
 * @package App\EventListener
 */
class RequestListener
{
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        // check if not is master request
        if (!$event->isMasterRequest()) {
            return;
        }

        /** @var Request $request */
        $request = $event->getRequest();

        //check if request content type is json
        if ($request->getContentType() &&
            ($request->getContentType() === 'application/json' || $request->getContentType() === 'json')) {

            $content = $request->getContent();

            if ($content) {
                $request->request->add(json_decode($content, true));
            }
        }
    }
}