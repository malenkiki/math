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

class Stats implements \Countable
{
    protected $arr = array();
    protected $int_count = null;

    protected $float_harmonic_mean = null;
    protected $float_geometric_mean = null;
    protected $float_arithmetic_mean = null;
    protected $float_root_mean_square = null;


    public function __get($name)
    {
        if(in_array($name, array('harmonic_mean', 'subcontrary_mean', 'H')))
        {
            return $this->harmonicMean();
        }
        
        if(in_array($name, array('geometric_mean', 'G')))
        {
            return $this->geometricMean();
        }
        
        if(in_array($name, array('arithmetic_mean', 'mean')))
        {
            return $this->arithmeticMean();
        }
        
        if(in_array($name, array('root_mean_square', 'rms', 'quadratic_mean')))
        {
            return $this->rootMeanSquare();
        }

    }


    public function __construct($arr = array())
    {
        $this->merge($arr);
    }


    public function count()
    {
        if(is_null($this->int_count))
        {
            $this->int_count = count($this->arr);
        }

        return $this->int_count;
    }



    public function merge($arr)
    {
        $this->arr = array_merge($this->arr, $arr);
        $this->int_count = null;
    }


    public function add($num)
    {
        if(!is_numeric($num))
        {
            throw new \InvalidArgumentException('Only  umeric values are allowed into statistical collection.');
        }
        $this->arr[] = (double) $num;
        $this->int_count = null;
    }

    public function arithmeticMean()
    {
        if(is_null($this->float_arithmetic_mean))
        {
            $this->float_arithmetic_mean = array_sum($this->arr) / count($this);
        }

        return $this->float_arithmetic_mean;
    }

    public function mean()
    {
        return $this->arithmeticMean();
    }


    public function harmonicMean()
    {
        if(is_null($this->float_harmonic_mean))
        {
            $arr = array();

            foreach($this->arr as $v)
            {
                $arr[] = 1 / $v;
            }

            $this->float_harmonic_mean = count($this) / array_sum($arr);
        }

        return $this->float_harmonic_mean;
    }


    public function geometricMean()
    {
        if(is_null($this->float_geometric_mean))
        {
            $this->float_geometric_mean = pow(
                array_product($this->arr),
                1 / count($this)
            );
        }

        return $this->float_geometric_mean;
    }


    public function rootMeanSquare()
    {
        if(is_null($this->float_root_mean_square))
        {
            $s = new self(
                array_map(
                    function($n){
                        return $n * $n;
                    },
                    $this->arr
                )
            );

            $this->float_root_mean_square = sqrt($s->mean);
        }

        return $this->float_root_mean_square;
    }

    public function rms()
    {
        return $this->rootMeanSquare();
    }

    public function quadraticMean()
    {
        return $this->rootMeanSquare();
    }
}
