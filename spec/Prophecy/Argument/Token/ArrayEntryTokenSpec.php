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

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function its_string_representation_tells_that_its_an_array_containing_the_key_value_pair($token)
    {
        $token->__toString()->willReturn('value');
        $this->beConstructedWith('key', $token);
        $this->__toString()->shouldBe('[..., exact("key") => value, ...]');
    }

    /**
     * @param \stdClass $object
     */
    function it_wraps_non_token_value_into_ExactValueToken($object)
    {
        $this->beConstructedWith('key', 5);
        $this->__toString()->shouldBe('[..., exact("key") => exact(5), ...]');

        $this->beConstructedWith('key', '5');
        $this->__toString()->shouldBe('[..., exact("key") => exact("5"), ...]');

        $hash = spl_object_hash($object->getWrappedObject());
        $class = get_class($object->getWrappedObject());
        $this->beConstructedWith('key',$object);
        $this->__toString()->shouldBe(sprintf('[..., exact("key") => exact(%s:%s), ...]', $class, $hash));
    }

    /**
     * @param \stdClass $object
     */
    function it_wraps_key_into_ExactValueToken($object)
    {
        $this->beConstructedWith(5, 5);
        $this->__toString()->shouldBe('[..., exact(5) => exact(5), ...]');

        $this->beConstructedWith('key', 5);
        $this->__toString()->shouldBe('[..., exact("key") => exact(5), ...]');

        $hash = spl_object_hash($object->getWrappedObject());
        $class = get_class($object->getWrappedObject());
        $this->beConstructedWith($object, 5);
        $this->__toString()->shouldBe(sprintf('[..., exact(%s:%s) => exact(5), ...]', $class, $hash));
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_scores_the_amount_scored_by_value_token_plus_one($token)
    {
        $token->scoreArgument('March')->willReturn(5);
        $this->beConstructedWith(3, $token);
        $argument = array(3 => 'March');
        $this->scoreArgument($argument)->shouldBe(6);
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function its_score_is_capped_at_8($token)
    {
        $token->scoreArgument('March')->willReturn(8);
        $this->beConstructedWith(3, $token);
        $argument = array(3 => 'March');
        $this->scoreArgument($argument)->shouldBe(8);
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_does_not_score_if_value_token_does_not_score($token)
    {
        $token->scoreArgument('March')->willReturn(false);
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
