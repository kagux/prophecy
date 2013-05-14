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
 * Array elements count token.
 *
 * @author Boris Mikhaylov <kaguxmail@gmail.com>
 */

class ArrayCountToken implements TokenInterface
{
    private $count;

    /**
     * @param integer $value array elements count
     */
    function __construct($value)
    {
        $this->count = $value;
    }

    /**
     * Scores 6 when argument has preset number of elements.
     *
     * @param $argument
     *
     * @return bool|int
     */
    public function scoreArgument($argument)
    {
        return (is_array($argument) || $argument instanceof \Countable ) && $this->count === count($argument)? 6 : false;
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
        return sprintf('count(%s)', $this->count);
    }
}
