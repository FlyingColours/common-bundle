<?php

namespace spec\FlyingColours\CommonBundle\Listener;

use FlyingColours\CommonBundle\Listener\TemplateResolverListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TemplateResolverListenerSpec extends ObjectBehavior
{
    function let(TemplateGuesser $guesser)
    {
        $this->beConstructedWith($guesser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TemplateResolverListener::class);
    }

    function it_listens_to_kernel_controller_event(
        FilterControllerEvent $event,
        Request $request,
        TemplateGuesser $guesser,
        ParameterBag $parameterBag
    )
    {
        $event->getController()->willReturn(array());
        $event->getRequest()->willReturn($request);

        $request->attributes = $parameterBag;

        $guesser->guessTemplateName(Argument::any(), Argument::any())->shouldBeCalled();

        $this->onKernelController($event);
    }
}
