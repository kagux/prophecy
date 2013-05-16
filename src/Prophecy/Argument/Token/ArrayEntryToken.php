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
    private $value;

    /**
     * @param mixed $key exact value or token
     * @param mixed $value exact value or token
     */
    function __construct($key, $value)
    {
        $this->key = $this->wrapIntoExactValueToken($key);
        $this->value =$this->wrapIntoExactValueToken($value);
    }

    /**
     * Scores half of combined scores from key and value tokens for same entry. Capped at 8.
     *
     * @param $argument
     *
     * @return bool|int
     */
    public function scoreArgument($argument)
    {
        if(!is_array($argument)){
            return false;
        }
        $key_scores = array_map(array($this->key,'scoreArgument'), array_keys($argument));
        $value_scores = array_map(array($this->value,'scoreArgument'), $argument);
        return max(array_map(function($v, $k){return $v && $k ? min(8,($k + $v)/2):false;}, $value_scores, $key_scores));
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
        return sprintf('[..., %s => %s, ...]', $this->key, $this->value);
    }

    /**
     * Returns key
     *
     * @return TokenInterface
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns value
     *
     * @return TokenInterface
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Wraps non token $value into ExactValueToken
     *
     * @param $value
     * @return ExactValueToken|TokenInterface
     */
    private function wrapIntoExactValueToken($value)
    {
        return $value instanceof TokenInterface ? $value : new ExactValueToken($value);
    }
}