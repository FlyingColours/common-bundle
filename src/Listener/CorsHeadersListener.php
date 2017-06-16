<?php

namespace FlyingColours\CommonBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsHeadersListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, XSRF-TOKEN');
        $response->headers->set('Access-Control-Expose-Headers', 'XSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Methods', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
    }
}
