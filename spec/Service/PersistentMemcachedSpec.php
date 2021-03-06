<?php

namespace spec\FlyingColours\CommonBundle\Service;

use FlyingColours\CommonBundle\Service\PersistentMemcached;
use Memcached;
use PhpSpec\ObjectBehavior;
use Psr\SimpleCache\CacheInterface;

class PersistentMemcachedSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PersistentMemcached::class);
        $this->shouldHaveType(Memcached::class);
        $this->shouldHaveType(CacheInterface::class);
    }

    function it_makes_sure_it_does_not_add_server_when_its_already_added()
    {
        $this->addServers([['localhost', 11211], ['localhost', 11212 ]])->shouldReturn(true);
        $this->addServers([['localhost', 11211], ['localhost', 11212 ]])->shouldReturn(false);
    }

    function it_does_the_same_with_addServer_method()
    {
        $this->addServer('localhost', 11211)->shouldReturn(true);
        $this->addServer('localhost', 11211)->shouldReturn(false);
    }

    function it_implements_CacheInterface()
    {
        $this->clear();
        $this->getMultiple([]);
        $this->setMultiple([], 0);
        $this->deleteMultiple([]);
        $this->has('something')->shouldReturn(false);
    }
}
