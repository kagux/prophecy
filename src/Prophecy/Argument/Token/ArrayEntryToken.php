<?php

namespace Prophecy\Argument\Token;

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Array entry token.
 *
 * @author Boris Mikhaylov <kaguxmail@gmail.com>
 */
class ArrayEntryToken implements TokenInterface
{
    /** @var \Prophecy\Argument\Token\ExactValueToken */
    private $key;
    /** @var \Prophecy\Argument\Token\ExactValueToken|\Prophecy\Argument\Token\TokenInterface */
    private $valueToken;

    /**
     * @param mixed $key associative array key
     * @param mixed $value array value token associated with $key
     */
    function __construct($key, $value)
    {
        $this->key = new ExactValueToken($key);
        $this->valueToken = $value instanceof TokenInterface ? $value : new ExactValueToken($value);
    }

    /**
     * Scores the amount scored by value token plus one, but capped at 8
     *
     * @param $argument
     *
     * @return bool|int
     */
    public function scoreArgument($argument)
    {
        if (!is_array($argument) || !array_key_exists($this->key->getValue(), $argument)){
            return false;
        }
        $score = $this->valueToken->scoreArgument($argument[$this->key->getValue()]);
        return false === $score? false : min(8,$score + 1);
    }

    /**
     * Returns false.
     *
     * @return bool|int
     */
    public function isLast()
    {
        return false;
    }

    /**
     * Returns string representation for token.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('contains(%s => %s)', $this->key, $this->valueToken);
    }
}
