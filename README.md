# Common Symfony classes
 
Common Symfony classes used throughout the projects

[![Build Status](https://travis-ci.org/FlyingColours/common-bundle.svg?branch=develop)](https://travis-ci.org/FlyingColours/common-bundle)
[![Coverage Status](https://coveralls.io/repos/github/FlyingColours/common-bundle/badge.svg?branch=develop)](https://coveralls.io/github/FlyingColours/common-bundle?branch=develop)

## Components

### Content Negotiation and Template Resolver Listener

Symfony Event Listener which works out right response content type based on "Accept" header.

```yml
# app/config/services.yml

parameters:

    priorities: [ 'application/json', 'text/html' ]

services:

    content.negotiator:
        class: Negotiation\Negotiator
        
    listener.template.resolver:
        class: FlyingColours\CommonBundle\Listener\TemplateResolverListener
        arguments: [ "@sensio_framework_extra.view.guesser" ]
        tags:
            - { name: kernel.event_listener, event: kernel.controller, method: onKernelController }
    
    listener.content.negotiation:
        class: FlyingColours\CommonBundle\Listener\ContentNegotiationListener
        arguments: [ "%priorities%", "@content.negotiator", "@serializer", "@templating" ]
        tags:
            - { name: kernel.event_listener, event: kernel.view, method: onKernelView }

```

### Persistent Memcached

If you ever experienced problem described [here](https://gist.github.com/K-Phoen/4327229#gistcomment-1297369)
then you want to use this class instead of default Memcached.

```yml
# app/config/services.yml

services:

    memcached:
        class: FlyingColours\CommonBundle\Service\PersistentMemcached
        arguments:
            persistent_id: "%session_prefix%"
        calls:
            - [ addServer, [ "%memcached_host%", "%memcached_port%" ]]

    session.handler.memcached:
        class:     Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler
        arguments: [ "@memcached", { prefix: "%session_prefix%", expiretime: "%session_expire%" }]
```
