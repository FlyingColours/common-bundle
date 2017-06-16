<?php

namespace spec\FlyingColours\CommonBundle;

use FlyingColours\CommonBundle\FlyingColoursCommonBundle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlyingColoursCommonBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FlyingColoursCommonBundle::class);
        $this->shouldHaveType(Bundle::class);
    }
}
