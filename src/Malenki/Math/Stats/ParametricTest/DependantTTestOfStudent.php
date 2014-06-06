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

namespace Malenki\Math\Stats\ParametricTest;
use \Malenki\Math\Stats\Stats;

class DependantTTestOfStudent implements \Countable
{
    protected $arr_samples = array();
    protected $int_count = null;
    protected $int_dof = null;
    protected $float_t = null;


    public function add($s)
    {
        if(count($this->arr_samples) == 2){
            throw new \RuntimeException(
                'Student’s t-Test For Dependant samples does not use more than two samples!'
            );
        }

        if(is_array($s)){
            $s = new Stats($s);
        } elseif(!($s instanceof Stats))
        {
            throw new \InvalidArgumentException(
                'Added sample to Student’s t-Test For Dependant samples must be array or Stats instance'
            );
        }

        if(count($this->arr_samples) == 1){
            if(count($s) != count($this->arr_samples[0])){
                throw new \RuntimeException(
                    'Student’s t-Test For Dependant samples must use two sample having the same size.'
                );
            }
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

    protected function clear()
    {
        //TODO
        $this->int_count = null;
    }


    public function count()
    {
        if(is_null($this->int_count)){
            $this->compute();
        }

        return $this->int_count;
    }


    public function dof()
    {
        if(is_null($this->int_dof)){
            $this->compute();
        }

        return $this->int_dof;
    }

    public function compute()
    {
        if(is_null($this->float_t)){
            $this->int_count = count($this->arr_samples[0]);
            $this->int_dof = $this->int_count - 1;

            $d = new \Malenki\MAth\Stats\Stats();

            for($i = 0; $i < $this->int_count; $i++){
                $d->add(
                    $this->arr_samples[1]->get($i)
                    -
                    $this->arr_samples[0]->get($i)
                );
            }

            $diff2 = new \Malenki\MAth\Stats\Stats();

            for($i = 0; $i < $this->int_count; $i++){
                $diff2->add($d->get($i) - $d->mean);
            }

            $sigma = sqrt($diff2->sum2 / ($this->int_count - 1));
        }
    }
}
