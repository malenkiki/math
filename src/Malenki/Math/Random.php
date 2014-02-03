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
 * If no arguments given, generates float values into the [0.0, 1.0] range. If 
 * the two arguments are integers, then generator will return value into this 
 * range.
 *
 * It can get one or many numbers, with or withour replacement.
 * 
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Random
{
    protected $range = null;

    public function __construct($int_min = null, $int_max = null)
    {
        $this->range = new \stdClass();

        if(is_integer($int_min) && is_integer($int_max) && $int_min < $int_max)
        {
            if($int_max > getrandmax())
            {
                throw new \OutOfRangeException('On this system, you cannot exceed %d integer value.', getrandmax());
            }
            $this->range->as_integer = true;
            $this->range->min = $int_min;
            $this->range->max = $int_max;
        }
        elseif(is_null($int_min) && is_null($int_max))
        {
            $this->range->as_integer = false;
            $this->range->min = 0;
            $this->range->max = 1;
        }
        else
        {
            throw new \InvalidArgumentException('Random range must be integers');
        }
    }

    public function get()
    {
        if($this->range->as_integer)
        {
            return rand($this->range->min, $this->range->max);
        }
        else
        {
            return rand(0, getrandmax()) / getrandmax();
        }
    }

    public function getMany($n)
    {
        if(!is_integer($n) || $n < 2)
        {
            throw new \InvalidArgumentException('You must take 2 or more items in this case.');
        }

        $arr_out = array();

        for($i = 0; $i < $n; $i++)
        {
            $arr_out[] = $this->get();
        }

        return $arr_out;
    }


    public function getManyWithoutReplacement($n)
    {
        if(!is_integer($n) || $n < 2)
        {
            throw new \InvalidArgumentException('You must take 2 or more items in this case.');
        }


        if($this->range->as_integer)
        {
            $arr_range = range($this->range->min, $this->range->max);
            $max_takable = count($arr_range);

            shuffle($arr_range);

            if($n > $max_takable)
            {
                throw new \OutOfRangeException(
                    sprintf(
                        'Cannot take without replacement more than available items into range [%d;%d]',
                        $this->range->min,
                        $this->range->max
                    )
                );
            }
            elseif($n == $max_takable)
            {
                return array_values($arr_range);
            }
            else
            {
                return array_slice($arr_range, 0, $n);
            }
        }
        else
        {
            $arr_out = array();

            while(count($arr_out) < $n)
            {
                $r = $this->get();
                
                if(!in_array($r, $arr_out))
                {
                    $arr_out[] = $r;
                }
            }

            return $arr_out;
        }


    }
}
