<?php

namespace spec\Prophecy\Argument\Token;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\TokenInterface;

class LogicalNotTokenSpec extends ObjectBehavior
{

    /**
     * @param \Prophecy\Argument\Token\TokenInterface $token
     */
    function let($token)
    {
        $this->beConstructedWith($token);
    }

    function it_implements_TokenInterface()
    {
        $this->beConstructedWith('value');
        $this->shouldBeAnInstanceOf('Prophecy\Argument\Token\TokenInterface');
    }

    function it_has_simple_string_representation(TokenInterface $token)
    {
        $token->__toString()->willReturn('value');
        $this->__toString()->shouldBe('not(value)');
    }

    function it_wraps_non_token_argument_into_ExactValueToken()
    {
        $this->beConstructedWith(5);
        $this->__toString()->shouldBe('not(exact(5))');
        $this->beConstructedWith('value');
        $this->__toString()->shouldBe('not(exact("value"))');
    }

    function it_scores_8_if_preset_token_does_not_match_the_argument(TokenInterface $token)
    {
        $token->scoreArgument('argument')->willReturn(false);
        $this->scoreArgument('argument')->shouldBe(8);
    }

    function it_does_not_score_if_preset_token_matches_argument(TokenInterface $token)
    {
        $token->scoreArgument('argument')->willReturn(5);
        $this->scoreArgument('argument')->shouldBe(false);
    }

    function it_is_last_if_preset_token_is_last(TokenInterface $token)
    {
        $token->isLast()->willReturn(true);
        $this->isLast()->shouldBe(true);
    }

    function it_is_not_last_if_preset_token_is_not_last(TokenInterface $token)
    {
        $token->isLast()->willReturn(false);
        $this->isLast()->shouldBe(false);
    }

}
