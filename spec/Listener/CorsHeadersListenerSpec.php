<?php

namespace spec\FlyingColours\CommonBundle\Listener;

use FlyingColours\CommonBundle\Listener\CorsHeadersListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CorsHeadersListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CorsHeadersListener::class);
    }

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
     */
    function it_sets_CORS_headers_required_for_embedding_scripts(FilterResponseEvent $event, Request $request, Response $response, ParameterBag $bag)
    {
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);

        $request->headers = $bag;

        $response->headers = $bag;

        $bag->get(Argument::any())->willReturn('some value');
        $bag->set(Argument::any(), Argument::any())->shouldBeCalled();

        $this->onKernelResponse($event);
    }

    function it_creates_response_for_OPTIONS_requests(GetResponseEvent $event, Request $request)
    {
        $event->getRequest()->willReturn($request);
        $event->setResponse(Argument::any())->shouldBeCalled();

        $request->isMethod(Request::METHOD_OPTIONS)->willReturn(true);

        $this->onKernelRequest($event);
    }
}
