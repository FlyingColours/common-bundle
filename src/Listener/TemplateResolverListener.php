<?php

namespace FlyingColours\CommonBundle\Listener;

use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TemplateResolverListener
{
    /** @var TemplateGuesser $guesser */
    private $guesser;

    /**
     * TemplateResolverListener constructor.
     * @param TemplateGuesser $guesser
     */
    public function __construct(TemplateGuesser $guesser)
    {
        $this->guesser = $guesser;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        /** @var array $controller */
        if ( ! is_array($controller = $event->getController())) return;

        $request = $event->getRequest();

        if( ! $request->attributes->has('template'))
        {
            $request->attributes->set('template', $this->guesser->guessTemplateName($controller, $request));
        }
    }
}
