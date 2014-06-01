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

namespace Malenki\Math\Stats\NonParametricTest;
use \Malenki\Math\Stats\Stats;

class WilcoxonMannWhitney implements \Countable
{
    protected $arr_samples = array();
    protected $u1 = null;
    protected $u2 = null;
    protected $u = null;


    public function __get($name)
    {
        if($name == 'u1' || $name == 'u2' || $name == 'u'){
            return $this->$name();
        }
    }


    public function add($s)
    {
        if(count($this->arr_samples) == 2){
            throw new \RuntimeException(
                'Wilcoxon-Mann-Whitney Test does not use more than two samples!'
            );
        }

        if(is_array($s)){
            $s = new Stats($s);
        } elseif(!($s instanceof Stats))
        {
            throw new \InvalidArgumentException(
                'Added sample to Wilcoxon-Mann-Whitney test must be array or Stats instance'
            );
        }

        $this->arr_samples[] = $s;
        $this->clear();

        return $this;
    }

    public function set($sampleOne, $sampleTwo)
    {
        $this->add($sampleOne);
        $this->add($sampleTwo);

        return $this;
    }

    public function clear()
    {
        //TODO
    }

    public function count()
    {
        return 0;
    }

    protected function compute()
    {
        if(is_null($this->u1) || is_null($this->u2)){
            $n1 = count($this->arr_samples[0]);
            $n2 = count($this->arr_samples[1]);
            $r1 = $this->arr_samples[0]->sum;
            $r2 = $this->arr_samples[1]->sum;
            $this->u1 =  $n1 * $n2 + ( 0.5 * $n1 * ($n1 + 1)) - $r1;
            $this->u2 =  $n1 * $n2 + ( 0.5 * $n2 * ($n2 + 1)) - $r2;
            $this->u = min($this->u1(), $this->u2());
        }
    }

    public function u1()
    {
        $this->compute();

        return $this->u1;
    }

    public function u2()
    {
        $this->compute();

        return $this->u2;
    }

    public function u()
    {
        $this->compute();
        return $this->u;
    }
}
