<?php

namespace spec\Prophecy\Argument\Token;

use PhpSpec\ObjectBehavior;

class LogicalNotTokenSpec extends ObjectBehavior
{
    function it_implements_TokenInterface()
    {
        $this->beConstructedWith('value');
        $this->shouldBeAnInstanceOf('Prophecy\Argument\Token\TokenInterface');
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_has_simple_string_representation($token)
    {
        $token->__toString()->willReturn('value');
        $this->beConstructedWith($token);
        $this->__toString()->shouldBe('not(value)');
    }

    function it_wraps_non_token_argument_into_ExactValueToken()
    {
        $this->beConstructedWith(5);
        $this->__toString()->shouldBe('not(exact(5))');
        $this->beConstructedWith('value');
        $this->__toString()->shouldBe('not(exact("value"))');
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_scores_8_if_preset_token_does_not_match_the_argument($token)
    {
        $token->scoreArgument('argument')->willReturn(false);
        $this->beConstructedWith($token);
        $this->scoreArgument('argument')->shouldBe(8);
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_does_not_score_if_preset_token_matches_argument($token)
    {
        $token->scoreArgument('argument')->willReturn(5);
        $this->beConstructedWith($token);
        $this->scoreArgument('argument')->shouldBe(false);
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_is_last_if_preset_token_is_last($token)
    {
        $token->isLast()->willReturn(true);
        $this->beConstructedWith($token);
        $this->isLast()->shouldBe(true);
    }

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function it_is_not_last_if_preset_token_is_not_last($token)
    {
        $token->isLast()->willReturn(false);
        $this->beConstructedWith($token);
        $this->isLast()->shouldBe(false);
    }

}
