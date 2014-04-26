<?php
/*
 * Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Malenki\Math;

/**
 * Random generator for floats or integers.
 *
 * If no arguments given, generates float values into the `[0.0, 1.0]` range. If
 * the two arguments are integers, then generator will return value into the
 * range created by the this two integers.
 *
 * It can get one or many random numbers, with or without replacement.
 *
 * Into string context, returns one random number.
 *
 * @author Michel Petit <petit.michel@gmail.com>
 * @license MIT
 */
class Random
{
    /**
     * Store the range as an object, with, min, maw and attribute to test if it
     * is float or integers.
     *
     * @var mixed
     * @access protected
     */
    protected $range = null;

    /**
     * Create one random nuber generator.
     *
     * If no argument provided, then will generate only float values between 0 and 1.
     *
     * If to integers are provided, then they will define the range where to
     * take random number(s).
     *
     * @param  integer $int_min
     * @param  integer $int_max
     * @access public
     * @return void
     */
    public function __construct($int_min = null, $int_max = null)
    {
        $this->range = new \stdClass();

        if (is_integer($int_min) && is_integer($int_max) && $int_min < $int_max) {
            if ($int_max > getrandmax()) {
                throw new \OutOfRangeException('On this system, you cannot exceed %d integer value.', getrandmax());
            }
            $this->range->as_integer = true;
            $this->range->min = $int_min;
            $this->range->max = $int_max;
        } elseif (is_null($int_min) && is_null($int_max)) {
            $this->range->as_integer = false;
            $this->range->min = 0;
            $this->range->max = 1;
        } else {
            throw new \InvalidArgumentException('Random range must be integers');
        }
    }

    /**
     * Gets at each call a random number into the defined range.
     *
     * Random float between 0 and 1 or integers inside the range defined at
     * instanciation time is returned.
     *
     * @access public
     * @return mixed Integer or float.
     */
    public function get()
    {
        if ($this->range->as_integer) {
            return rand($this->range->min, $this->range->max);
        } else {
            return rand(0, getrandmax()) / getrandmax();
        }
    }

    /**
     * Gets many random value.
     *
     * Gets many random values, with the same behaviour as `get()` method.
     *
     * @param  integer $n Number of items to get. Must be greater than 1
     * @throw \InvalidArgumentException If argument is not integer or is less than 2
     * @access public
     * @return array
     */
    public function getMany($n)
    {
        if (!is_integer($n) || $n < 2) {
            throw new \InvalidArgumentException('You must take 2 or more items in this case.');
        }

        $arr_out = array();

        for ($i = 0; $i < $n; $i++) {
            $arr_out[] = $this->get();
        }

        return $arr_out;
    }

    /**
     * Gets many random items without replacement.
     *
     * @param  integer $n Number of items to get. Must be greater than 1
     * @throw \InvalidArgumentException If argument is not integer or is less than 2
     * @throw \OutOfRangeException If argument is bigger than ammount of
     *                    integers into the range (integer range context only)
     * @access public
     * @return array
     */
    public function getManyWithoutReplacement($n)
    {
        if (!is_integer($n) || $n < 2) {
            throw new \InvalidArgumentException('You must take 2 or more items in this case.');
        }

        if ($this->range->as_integer) {
            $arr_range = range($this->range->min, $this->range->max);
            $max_takable = count($arr_range);

            shuffle($arr_range);

            if ($n > $max_takable) {
                throw new \OutOfRangeException(
                    sprintf(
                        'Cannot take without replacement more than available items into range [%d;%d]',
                        $this->range->min,
                        $this->range->max
                    )
                );
            } elseif ($n == $max_takable) {
                return array_values($arr_range);
            } else {
                return array_slice($arr_range, 0, $n);
            }
        } else {
            $arr_out = array();

            while (count($arr_out) < $n) {
                $r = $this->get();

                if (!in_array($r, $arr_out)) {
                    $arr_out[] = $r;
                }
            }

            return $arr_out;
        }

    }

    /**
     * Into string context, returns one random number.
     *
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return (string) $this->get();
    }
}
