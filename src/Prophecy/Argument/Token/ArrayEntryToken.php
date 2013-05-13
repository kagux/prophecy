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
    private $key;
    private $value;

    /**
     * @param mixed $key associative array key
     * @param mixed $value array value associated with $key
     */
    function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Scores 8 if argument is an array and it contains preset (key, value) pair
     *
     * @param $argument
     *
     * @return bool|int
     */
    public function scoreArgument($argument)
    {
        return is_array($argument) &&
               array_key_exists($this->key, $argument) &&
               $argument[$this->key] === $this->value ? 8 : false;
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
        return sprintf('array(..., [%s] => [%s], ...)', $this->key, $this->value);
    }
}
