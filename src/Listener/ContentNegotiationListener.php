<?php

namespace FlyingColours\CommonBundle\Listener;

use Negotiation\Accept;
use Negotiation\Negotiator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ContentNegotiationListener
{
    /** @var array */
    private $priorities;

    /** @var Negotiator */
    private $negotiator;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * ContentNegotiationListener constructor.
     * @param array $priorities
     * @param Negotiator $negotiator
     * @param SerializerInterface $serializer
     */
    public function __construct(array $priorities, Negotiator $negotiator, SerializerInterface $serializer)
    {
        $this->priorities = $priorities;
        $this->negotiator = $negotiator;
        $this->serializer = $serializer;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if( ! $result instanceof Response)
        {
            /** @var Accept $accept */
            $accept = $this->negotiator->getBest($request->headers->get('Accept'), $this->priorities);

            switch ($accept->getType())
            {
                case 'application/xml':
                    $response = new Response($this->serializer->serialize($result, 'xml', ['groups' => ['api']]));
                    break;

                default:
                    $response = new Response($this->serializer->serialize($result, 'json', ['groups' => ['api']]));
            }

            $response->headers->add(['Content-Type' => $accept->getType()]);
            $event->setResponse($response);
        }
    }
}
