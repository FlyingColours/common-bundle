<?php

namespace spec\FlyingColours\CommonBundle\Listener;

use FlyingColours\CommonBundle\Listener\ContentNegotiationListener;
use Negotiation\BaseAccept;
use Negotiation\Negotiator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ContentNegotiationListenerSpec extends ObjectBehavior
{
    function let(Negotiator $negotiator, SerializerInterface $serializer)
    {
        $this->beConstructedWith($priorities = ['application/json'], $negotiator, $serializer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContentNegotiationListener::class);
    }

    function it_can_work_out_which_format_use_in_output(
        GetResponseForControllerResultEvent $event,
        Request $request,
        ParameterBag $bag,
        Negotiator $negotiator,
        BaseAccept $accept,
        SerializerInterface $serializer
    )
    {
        $event->getControllerResult()->willReturn([]);
        $event->getRequest()->willReturn($request);
        $event->setResponse(Argument::any())->shouldBeCalled();

        $request->getRequestFormat()->willReturn('json');
        $request->headers = $bag;

        $negotiator->getBest(Argument::any(), Argument::any())->willReturn($accept);
        $accept->getType()->willReturn('application/json');

        $serializer->serialize(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->onKernelView($event);

        $request->getRequestFormat()->willReturn('xml');
        $accept->getType()->willReturn('application/xml');

        $this->onKernelView($event);
    }
}
