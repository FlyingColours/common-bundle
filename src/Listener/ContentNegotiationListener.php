<?php

namespace FlyingColours\CommonBundle\Listener;

use Negotiation\Accept;
use Negotiation\Negotiator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;

class ContentNegotiationListener
{
    /** @var array */
    private $priorities;

    /** @var Negotiator */
    private $negotiator;

    /** @var SerializerInterface */
    private $serializer;

    /** @var Environment */
    private $twig;

    /**
     * ContentNegotiationListener constructor.
     *
     * @param array $priorities
     * @param Negotiator $negotiator
     * @param SerializerInterface $serializer
     * @param Environment $twig
     */
    public function __construct(array $priorities, Negotiator $negotiator, SerializerInterface $serializer, Environment $twig)
    {
        $this->priorities = $priorities;
        $this->negotiator = $negotiator;
        $this->serializer = $serializer;
        $this->twig = $twig;
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();

        if( ! $result instanceof Response)
        {
            /** @var Accept $accept */
            $accept = $this->negotiator->getBest($request->headers->get('Accept'), $this->priorities);

            $groups = interface_exists('Knp\Component\Pager\Pagination\PaginationInterface') && $result instanceof \Knp\Component\Pager\Pagination\PaginationInterface
                ? [ 'api_list']
                : [ 'api' ]
            ;

            switch ($accept->getType())
            {
                case 'application/xml':
                    $response = new Response($this->serializer->serialize($result, 'xml', [ 'groups' => $groups ]));
                    break;

                case 'text/html':
                    $response = new Response($this->twig->render($request->attributes->get('template'), $result));
                    break;

                default:
                    $response = new Response($this->serializer->serialize($result, 'json', [ 'groups' => $groups ]));
            }

            $response->headers->add(['Content-Type' => $accept->getType()]);

            $event->setResponse($response);
        }
    }
}
