<?php

namespace FlyingColours\CommonBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CorsHeadersListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $accessControlExposeHeaders = [ 'CSRF-TOKEN' ];

        if ($response->getStatusCode() === 201) {
            // expose location in case of 201 response
            $accessControlExposeHeaders[] = 'Location';
        }

        $accessControlExposeHeaders = $accessControlExposeHeaders + explode(',', $response->headers->get('Access-Control-Expose-Headers', ''));

        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Headers', $request->headers->get('Access-Control-Request-Headers'));
        $response->headers->set('Access-Control-Expose-Headers', join(',', $accessControlExposeHeaders));
        $response->headers->set('Access-Control-Allow-Methods', $request->headers->get('Access-Control-Request-Method'));
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if($event->getRequest()->isMethod(Request::METHOD_OPTIONS))
        {
            $event->setResponse(new Response());
        }
    }
}
