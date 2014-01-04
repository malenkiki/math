<?php
/*
 * Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>
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

class NormalDistribution
{
    protected $float_sigma = 0;
    protected $float_mu = 0;
    protected $int_precision = 0;


    public function __construct($sigma, $mu)
    {
        if(!is_numeric($sigma) || !is_numeric($mu))
        {
            throw new \InvalidArgumentException('Sigma and Mu must be number.');
        }

        if($mu < 0)
        {
            throw new \InvalidArgumentException('µ must be a positive real number.');
        }

        $this->float_sigma = $sigma;
        $this->float_mu = $mu;
    }



    public function precision($n)
    {
        if(!is_numeric($n) || $n < 0)
        {
            throw new \InvalidArgumentException('Precision must be positive number');
        }

        $this->int_precision = (integer) $n;
    }


    public function f($x)
    {
        if(!is_numeric($x))
        {
            throw new \InvalidArgumentException('x variable must be numeric value.');
        }

        $float_fx = exp(-0.5 * pow(($x - $this->float_mu) / $this->float_sigma, 2)) / ($this->float_sigma * sqrt(2 * pi()));

        if($this->int_precision)
        {
            return round($float_fx, $this->int_precision);
        }

        return $float_fx;
    }


    public function fn(array &$arr_x)
    {
        $arr = array();

        foreach($arr_x as $x)
        {
            $item = new \stdClass();
            $item->x = $x;
            $item->fx = $this->f($x);
            $arr[] = $item;
        }

        return $arr;
    }
}
