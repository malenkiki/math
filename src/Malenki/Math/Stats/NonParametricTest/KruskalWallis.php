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

class KruskalWallis implements \Countable
{
    protected $int_count = null;
    protected $arr_samples = array();
    protected $arr_ranks = array();
    protected $arr_rank_values = array();
    protected $arr_rank_samples = array();
    protected $arr_rank_sums = array();
    protected $arr_rank_means = array();
    
    
    public function add($s)
    {
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


    public function clear()
    {
        $this->int_count = null;
    }


    public function count()
    {
        if(is_null($this->int_count)){
            $this->int_count = 0;

            foreach($this->arr_samples as $s){
                $this->int_count += count($s);
            }
        }

        return $this->int_count;
    }


    protected function compute()
    {
        $this->computeRanks();
    }

    protected function computeRanks()
    {
        foreach($this->arr_samples as $k => $s){
            $this->arr_rank_values = array_merge(
                $this->arr_rank_values,
                $s->array
            );

            $this->arr_rank_samples = array_merge(
                $this->arr_rank_samples,
                array_pad(
                    array(),
                    count($s),
                    $k
                )
            );
        }

        array_multisort(
            $this->arr_rank_values, 
            SORT_ASC,
            SORT_NUMERIC,
            $this->arr_rank_samples
        );
    }


    public function rankSum($n)
    {
        $this->compute();

        //return $this->arr_rank_sums[$n];
    }

    public function rankMean($n)
    {
        $this->compute();

        //return $this->arr_rank_means[$n];
    }
}
