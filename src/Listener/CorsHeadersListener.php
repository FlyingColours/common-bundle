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

        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, XSRF-TOKEN');
        $response->headers->set('Access-Control-Expose-Headers', 'XSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Methods', '*');
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
