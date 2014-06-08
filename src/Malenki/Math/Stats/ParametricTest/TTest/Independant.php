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

class Independant
{
    protected $arr_samples = array();
    protected $int_dof = null;
    protected $float_sigma = null;
    protected $float_t = null;
    protected $float_mean = null;

    public function __set($name, $value)
    {
        if(
            in_array(
                $name,
                array(
                    'sample_one',
                    'sample_two',
                    'sample_1',
                    'sample_2',
                    'sample_a',
                    'sample_b',
                )
            )
        )
        {
            if (is_array($value)) {
                $value = new Stats($value);
            } elseif (!($value instanceof Stats)) {
                throw new \InvalidArgumentException(
                    'Added sample to Dependant t-test of Student must be array or Stats instance'
                );
            }

            if (preg_match('/_(1|one|a)$/',$name)) {
                $this->arr_samples[0] = $value;
            } else {
                $this->arr_samples[1] = $value;
            }

            $this->clear();
        }
    }

    public function add($s)
    {
        if (count($this->arr_samples) == 2) {
            throw new \RuntimeException(
                'Student’s t-Test For Independant samples does not use more than two samples!'
            );
        }

        if (is_array($s)) {
            $s = new Stats($s);
        } elseif (!($s instanceof Stats)) {
            throw new \InvalidArgumentException(
                'Added sample to Student’s t-Test For Independant samples must be array or Stats instance'
            );
        }

        $this->arr_samples[] = $s;
        $this->clear();

        return $this;
    }

    public function set($sample_one, $sample_two)
    {
        $this->add($sample_one);
        $this->add($sample_two);

        return $this;
    }

    public function clear()
    {
        $this->int_dof = null;
        $this->float_sigma = null;
        $this->float_t = null;
    }

    public function mean()
    {
        $this->compute();

        return $this->float_mean;
    }

    protected function compute()
    {
        if(is_null($this->float_t)){
            $this->float_mean = $this->arr_samples[0]->mean;
            $this->float_mean -= $this->arr_samples[1]->mean;
        }
    }
}
