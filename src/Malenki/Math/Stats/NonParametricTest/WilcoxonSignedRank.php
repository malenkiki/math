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

class WilcoxonSignedRank implements \Countable
{
    protected $arr_samples = array();
    protected $arr_signs = array();
    protected $arr_abs = array();
    protected $arr_ranks = array();
    protected $arr_signed_ranks = array();
    protected $int_nr = null;


    public function add($s)
    {
        if(count($this->arr_samples) == 2){
            throw new \RuntimeException(
                'Wilcoxon Signed-Rank Test does not use more than two samples!'
            );
        }

        if(is_array($s)){
            $s = new Stats($s);
        } elseif(!($s instanceof Stats))
        {
            throw new \InvalidArgumentException(
                'Added sample to Wilcoxon Signed-Rank test must be array or Stats instance'
            );
        }

        if(count($this->arr_samples) == 1){
            if(count($s) != count($this->arr_samples[0])){
                throw new \RuntimeException(
                    'Wilcoxon Signed-Rank test must use two sample having the same size.'
                );
            }
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

    protected function clear()
    {
        //TODO
    }

    protected function compute()
    {
        $arr_1 = $this->arr_samples[0]->array;
        $arr_2 = $this->arr_samples[1]->array;
        $arr_sign = array();

        foreach($arr_1 as $k => $v){
            $diff = $arr_2[$k] - $v;

            if($diff == 0){
                $arr_sign[$k] = 0;
            } elseif($diff > 0){
                $arr_sign[$k] = 1;
            } else {
                $arr_sign[$k] = -1;
            }

            $this->arr_abs[$k] = abs($diff);
        }

        asort($this->arr_abs);

        foreach($this->arr_abs as $k => $v){
            $this->arr_signs[] = $arr_sign[$k];
        }

        $this->arr_abs = array_values($this->arr_abs);
        $this->computeRanks();
    }


    protected function computeRanks()
    {
        $int_size = count($this->arr_abs);

        $prev = null;
        $stats = null;
        $i = 1;
        foreach($this->arr_abs as $k => $c){
            //$c = $this->arr_abs[$i];
            if($c == 0){
                $this->arr_ranks[$k] = 0;
                continue;
            }

            if($c === $prev){

                if(is_null($stats)){
                    $stats = new \Malenki\Math\Stats\Stats();
                    $stats->add($i - 1);
                }
             
                $stats->add($i);
                
            } else {
                if(!is_null($stats)){
                    for($j = ($i - count($stats)); $j < $i; $j++){
                        $this->arr_ranks[$j] = $stats->mean;
                        $this->arr_signed_ranks[$j] = $stats->mean * $this->arr_signs[$j];
                    }
                    $stats = null;
                }

                $this->arr_ranks[$k] = $i;
                $this->arr_signed_ranks[$k] = $i * $this->arr_signs[$k];
            }

            $prev = $c;
            $i++;
        }


        if(!is_null($stats)){
            for($j = ($int_size - count($stats)); $j < $int_size; $j++){
                $this->arr_ranks[$j] = $stats->mean;
                $this->arr_signed_ranks[$j] = $stats->mean * $this->arr_signs[$j];
            }
        }

        $this->arr_ranks = array_filter($this->arr_ranks);
        $this->arr_signed_ranks = array_filter($this->arr_signed_ranks);
    }

    public function signs()
    {
        if(count($this->arr_signs) == 0){
            $this->compute();
        }

        return $this->arr_signs;
    }



    public function absoluteValues()
    {
        if(count($this->arr_abs) == 0){
            $this->compute();
        }

        return $this->arr_abs;
    }



    public function ranks()
    {
        if(count($this->arr_ranks) == 0){
            $this->compute();
        }

        return $this->arr_ranks;
    }



    public function signedRanks()
    {
        if(count($this->arr_signed_ranks) == 0){
            $this->compute();
        }

        return $this->arr_signed_ranks;
    }

    public function nr()
    {
        if(is_null($this->int_nr)){
            $this->int_nr = count($this->signedRanks());
        }

        return $this->int_nr;
    }

    public function count()
    {
        return $this->nr();
    }
}