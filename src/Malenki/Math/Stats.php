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
    protected $float_range = null;


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
        
        if(in_array($name, array('arithmetic_mean', 'mean', 'A', 'mu')))
        {
            return $this->arithmeticMean();
        }
        
        if(in_array($name, array('root_mean_square', 'rms', 'quadratic_mean', 'Q')))
        {
            return $this->rootMeanSquare();
        }
        
        if(in_array($name, array('midrange', 'midextreme', 'mid_range', 'mid_extreme')))
        {
            return $this->midrange();
        }

        if($name == 'range')
        {
            return $this->range();
        }
        
        if(in_array($name, array('variance', 'var')))
        {
            return $this->variance();
        }
        
        if(in_array($name, array('stdev', 'standard_deviation', 'sigma')))
        {
            return $this->standardDeviation();
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

    public function allPositive()
    {
        for($i = 0; $i < count($this); $i++)
        {
            if($this->arr[$i] < 0)
            {
                return false;
            }
        }

        return true;
    }


    protected function clear()
    {
        $this->int_count = null;

        $this->float_harmonic_mean = null;
        $this->float_geometric_mean = null;
        $this->float_arithmetic_mean = null;
        $this->float_root_mean_square = null;
        $this->float_range = null;
    }

    public function merge($arr)
    {
        $this->arr = array_merge($this->arr, $arr);
        $this->clear();

        return $this;
    }


    public function add($num)
    {
        if(!is_numeric($num))
        {
            throw new \InvalidArgumentException('Only  umeric values are allowed into statistical collection.');
        }
        $this->arr[] = (double) $num;
        $this->clear();

        return $this;
    }


    public function range()
    {
        if(is_null($this->float_range))
        {
            $this->float_range = max($this->arr) - min($this->arr);
        }

        return $this->float_range;
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


    public function generalizedMean($p)
    {
        if($p <= 0)
        {
            throw new \InvalidArgumentException('Generalized mean takes p as non-zero positive real number.');
        }

        if(!$this->allPositive())
        {
            throw new \RuntimeException('Power mean use only collection of positive numbers!');
        }

        $arr = array();

        for($i = 0; $i < count($this); $i++)
        {
            $arr[] = pow($this->arr[$i], $p);
        }

        return pow(array_sum($arr) / count($this), 1/$p);
    }

    public function powerMean($p)
    {
        return $this->generalizedMean($p);
    }


    public function midrange()
    {
        $s = new self();
        $s->add(max($this->arr));
        $s->add(min($this->arr));

        return $s->mean;
    }

    public function midextreme()
    {
        return $this->midrange();
    }

    public function variance()
    {
        //TODO
        return 0;
    }

    public function standardDeviation()
    {
        return sqrt($this->variance());
    }

    public function stdev()
    {
        return $this->standardDeviation();
    }
}
