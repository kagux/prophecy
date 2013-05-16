<?php

namespace spec\Prophecy\Argument\Token;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayEntryTokenSpec extends ObjectBehavior
{
    /**
     * @param \Prophecy\Argument\Token\TokenInterface $key
     * @param \Prophecy\Argument\Token\TokenInterface $value
     */
    function let($key, $value)
    {
        $this->beConstructedWith($key, $value);
    }

    function it_implements_TokenInterface()
    {
        $this->shouldBeAnInstanceOf('Prophecy\Argument\Token\TokenInterface');
    }

    function it_is_not_last()
    {
        $this->shouldNotBeLast();
    }

    function it_holds_key_and_value($key, $value)
    {
        $this->getKey()->shouldBe($key);
        $this->getValue()->shouldBe($value);
    }

    function its_string_representation_tells_that_its_an_array_containing_the_key_value_pair($key, $value)
    {
        $key->__toString()->willReturn('key');
        $value->__toString()->willReturn('value');
        $this->__toString()->shouldBe('[..., key => value, ...]');
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $key
     * @param \stdClass $object
     */
    function it_wraps_non_token_value_into_ExactValueToken($key, $object)
    {
        $this->beConstructedWith($key, $object);
        $this->getValue()->shouldHaveType('\Prophecy\Argument\Token\ExactValueToken');
    }

    /**
     * @param \stdClass $object
     * @param \Prophecy\Argument\Token\TokenInterface $value
     */
    function it_wraps_non_token_key_into_ExactValueToken($object, $value)
    {
        $this->beConstructedWith($object, $value);
        $this->getKey()->shouldHaveType('\Prophecy\Argument\Token\ExactValueToken');
    }

    function it_scores_half_of_combined_scores_from_key_and_value_tokens($key, $value)
    {
        $key->scoreArgument('key')->willReturn(4);
        $value->scoreArgument('value')->willReturn(6);
        $this->scoreArgument(array('key'=>'value'))->shouldBe(5);
    }

    function it_does_not_score_if_argument_is_not_an_array()
    {
        $this->scoreArgument('string')->shouldBe(false);
    }

    function it_does_not_score_if_key_token_does_not_score_any_of_argument_array_keys($key)
    {
        $argument = array(1 => 'foo', 2 => 'bar');
        $key->scoreArgument(1)->willReturn(false);
        $key->scoreArgument(2)->willReturn(false);
        $this->scoreArgument($argument)->shouldBe(false);
    }

    function it_does_not_score_if_value_token_does_not_score_any_of_argument_array_values($value)
    {
        $argument = array(1 => 'foo', 2 => 'bar');
        $value->scoreArgument('foo')->willReturn(false);
        $value->scoreArgument('bar')->willReturn(false);
        $this->scoreArgument($argument)->shouldBe(false);
    }

    function it_does_not_score_if_key_and_value_tokens_do_not_score_same_entry_of_argument_array($key, $value)
    {
        $argument = array(1 => 'foo', 2 => 'bar');
        $key->scoreArgument(1)->willReturn(true);
        $key->scoreArgument(2)->willReturn(false);
        $value->scoreArgument('foo')->willReturn(false);
        $value->scoreArgument('bar')->willReturn(true);
        $this->scoreArgument($argument)->shouldBe(false);
    }

    function its_score_is_capped_at_8($key, $value)
    {
        $key->scoreArgument('key')->willReturn(10);
        $value->scoreArgument('value')->willReturn(10);
        $this->scoreArgument(array('key'=>'value'))->shouldBe(8);
    }
}
