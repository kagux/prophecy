<?php

namespace spec\Prophecy\Argument\Token;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayEntryTokenSpec extends ObjectBehavior
{

    function it_implements_TokenInterface()
    {
        $this->beConstructedWith('key', 'value');
        $this->shouldBeAnInstanceOf('Prophecy\Argument\Token\TokenInterface');
    }

    function it_is_not_last()
    {
        $this->beConstructedWith('key', 'value');
        $this->shouldNotBeLast();
    }

    function its_string_representation_tells_that_its_an_array_containing_the_key_value_pair()
    {
        $this->beConstructedWith('key', 'value');
        $this->__toString()->shouldBe('array(..., [key] => [value], ...)');
    }

    function it_scores_8_if_argument_array_contains_key_with_value()
    {
        $this->beConstructedWith(3, 'March');
        $argument = array(3 => 'March');
        $this->scoreArgument($argument)->shouldBe(8);
    }

    function it_does_not_score_if_argument_array_has_the_key_with_different_value()
    {
        $this->beConstructedWith(3, 'March');
        $argument = array(3 => 'April');
        $this->scoreArgument($argument)->shouldBe(false);
    }

    function it_does_not_score_if_argument_array_does_not_have_the_key()
    {
        $this->beConstructedWith(3, 'March');
        $argument = array(4 => 'April');
        $this->scoreArgument($argument)->shouldBe(false);
    }

    function it_does_not_score_if_argument_is_not_an_array()
    {
        $this->beConstructedWith('key', 'value');
        $this->scoreArgument('string')->shouldBe(false);
    }

}
