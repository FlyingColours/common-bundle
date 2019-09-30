<?php

namespace spec\FlyingColours\CommonBundle\Listener;

use FlyingColours\CommonBundle\Listener\ContentNegotiationListener;
use Negotiation\BaseAccept;
use Negotiation\Negotiator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;

class ContentNegotiationListenerSpec extends ObjectBehavior
{
    function let(Negotiator $negotiator, SerializerInterface $serializer, Environment $engine)
    {
        $this->beConstructedWith($priorities = ['application/json'], $negotiator, $serializer, $engine);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContentNegotiationListener::class);
    }

    function it_can_work_out_which_format_use_in_output(
        ViewEvent $event,
        Request $request,
        Response $response,
        ParameterBag $bag,
        Negotiator $negotiator,
        BaseAccept $accept,
        SerializerInterface $serializer,
        Environment $engine
    )
    {
        $event->getControllerResult()->willReturn([]);
        $event->getRequest()->willReturn($request);
        $event->setResponse(Argument::any())->shouldBeCalled();

        $bag->get('Accept')->willReturn('application/json');
        $request->headers = $bag;
        $request->attributes = $bag;

        $negotiator->getBest(Argument::any(), Argument::any())->willReturn($accept);
        $accept->getType()->willReturn('application/json');

        $serializer->serialize(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->onKernelView($event);

        $bag->get('Accept')->willReturn('application/xml');
        $accept->getType()->willReturn('application/xml');

        $this->onKernelView($event);

        $bag->get('Accept')->willReturn('text/html,application/xhtml+xml');
        $bag->get('template')->shouldBeCalled();

        $engine->render(Argument::any(), Argument::any())->shouldBeCalled();

        $response->headers = $bag;

        $accept->getType()->willReturn('text/html');

        $this->onKernelView($event);
    }
}
