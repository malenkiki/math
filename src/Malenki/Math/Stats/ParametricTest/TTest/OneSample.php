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

namespace Malenki\Math\Stats\ParametricTest\TTest;
use \Malenki\Math\Stats\Stats;

class OneSample implements \Countable
{
    protected $sample = null;
    protected $float_stddev = null;
    protected $float_sigma2 = null;
    protected $float_sigma_pop = null;
    protected $float_t = null;

    public function __construct($mean = null)
    {
        if(!is_null($mean)){
            $this->populationMaan($mean);
        }
    }

    public function set($s)
    {
        if (is_array($s)) {
            $s = new Stats($s);
        } elseif (!($s instanceof Stats)) {
            throw new \InvalidArgumentException(
                'Added sample to Student’s t-Test For One Sample must be '
                .'array or Stats instance'
            );
        }

        $this->sample = $s;
        $this->clear();

        return $this;
    }

    public function populationMean($mean)
    {
        if(!is_numeric($mean)){
            throw new \InvalidArgumentException(
                'Population to compare sample with must have valid mean value!'
            );
        }

        $this->float_mean_pop = $mean;

        return $this;
    }

    public function clear()
    {
        $this->float_stddev = null;
        $this->float_sigma2 = null;
        $this->float_t = null;

        return $this;
    }



    protected function compute()
    {
        if(is_null($this->float_t)){
            $this->float_sigma2 = pow($this->sample->stddev, 2);
            $this->float_sigma2 *= count($this->sample);
            $this->float_sigma2 /= (count($this->sample) - 1);

            $this->float_stddev = $this->float_sigma2 / count($this->sample);
            $this->float_stddev = sqrt($this->float_stddev);

            $this->float_t = $this->sample->mean - $this->float_mean_pop;
            $this->float_t /= $this->float_stddev;
        }
    }

    public function count()
    {
        return count($this->sample);
    }

    public function sigma2()
    {
        $this->compute();

        return $this->float_sigma2;
    }


    public function standardDeviation()
    {
        $this->compute();

        return $this->float_stddev;
    }

    public function t()
    {
        $this->compute();

        return $this->float_t;
    }
}
