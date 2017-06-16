<?php

namespace spec\FlyingColours\CommonBundle\Listener;

use FlyingColours\CommonBundle\Listener\CorsHeadersListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

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

        $bag->get('Origin')->willReturn('some.server.co.uk');
        $bag->set(Argument::any(), Argument::any())->shouldBeCalled();

        $this->onKernelResponse($event);
    }
}
