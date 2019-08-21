<?php

namespace spec\FlyingColours\CommonBundle\Service;

use FlyingColours\CommonBundle\Service\InMemoryCache;
use PhpSpec\ObjectBehavior;
use Psr\SimpleCache\CacheInterface;

class InMemoryCacheSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryCache::class);
        $this->shouldHaveType(CacheInterface::class);
    }

    function it_implements_CacheInterface()
    {
        $this->clear();
        $this->has('something')->shouldReturn(false);
    }

    function it_can_get_or_return_null()
    {
        $this->get('something')->shouldReturn(null);
        $this->set('foo', 'bar');
        $this->get('foo')->shouldReturn('bar');
    }

    function it_can_delete_from_cache()
    {
        $this->set('foo', 'bar');
        $this->get('foo')->shouldReturn('bar');
        $this->delete('foo');
        $this->get('foo')->shouldReturn(null);
    }
}
