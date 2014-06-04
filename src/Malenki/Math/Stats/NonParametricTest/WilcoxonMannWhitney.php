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
    protected $int_count = null;
    protected $arr_samples = array();
    protected $arr_ranks = array();
    protected $arr_rank_samples = array();
    protected $arr_rank_values = array();
    protected $arr_rank_sums = array();
    protected $arr_rank_means = array();
    protected $arr_rank_sigmas = array();
    protected $u1 = null;
    protected $u2 = null;
    protected $u = null;
    protected $sigma = null;
    protected $sigma_corrected = null;
    protected $sigma2 = null;
    protected $sigma2_corrected = null;
    protected $mean = null;
    protected $z = null;
    protected $z_corrected = null;


    public function __get($name)
    {
        if(in_array($name, array('u1','u2','u', 'sigma', 'mean', 'sigma2', 'z'))){
            return $this->$name();
        }

        if($name == 'mu'){
            return $this->mean();
        }

        if(in_array($name, array('std', 'stdev', 'stddev'))){
            return $this->sigma();
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
        $this->compute();

        return $this->int_count;
    }

    protected function compute()
    {
        if(is_null($this->u1) || is_null($this->u2)){

            $this->computeRanks();

            $arr = array();
            foreach($this->arr_ranks as $idx => $r){
                $ns = $this->arr_rank_samples[$idx];

                if(!array_key_exists($ns, $arr)){
                    $arr[$ns] = new \Malenki\Math\Stats\Stats();
                }

                $arr[$ns]->add($r);
            }

            foreach($arr as $ns => $s){
                $this->arr_rank_sums[$ns] = $arr[$ns]->sum;
                $this->arr_rank_means[$ns] = $arr[$ns]->mean;
            }

            $n1 = count($this->arr_samples[0]);
            $n2 = count($this->arr_samples[1]);

            $r1 = $this->arr_rank_sums[0];
            $r2 = $this->arr_rank_sums[1];
            
            $m1 = $this->arr_rank_means[0];
            $m2 = $this->arr_rank_means[1];
            
            $this->int_count = $n1 + $n2;
            $this->u1 =  $n1 * $n2 + ( 0.5 * $n1 * ($n1 + 1)) - $r1;
            $this->u2 =  $n1 * $n2 + ( 0.5 * $n2 * ($n2 + 1)) - $r2;
            $this->u = min($this->u1(), $this->u2());
            $this->mean = 0.5 * $n1 * $n2;
            $this->sigma2 = $n1 * $n2 * ($n1 + $n2 + 1) / 12;
            //$this->sigma2_corrected = 0; // TODO
            $this->sigma = sqrt($this->sigma2);
            //$this->sigma_corrected = 0; // TODO
            $this->z = ($this->u - $this->mean) / $this->sigma;
            //$this->z_corrected = ($this->u - $this->mean) / $this->sigma_corrected; //TODO
        }
    }


    protected function correction()
    {
        //TODO
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
        
        
        $prev = null;
        $stats = null;
        $i = 1;

        foreach($this->arr_rank_values as $k => $c){
            if($c == $prev){

                if(is_null($stats)){
                    $stats = new \Malenki\Math\Stats\Stats();
                    $stats->add($i - 1);
                }
             
                $stats->add($i);
                
            } else {
                if(!is_null($stats)){
                    foreach($this->arr_ranks as $ri => $rv){
                        if(in_array($rv, $stats->array)){
                            $this->arr_ranks[$ri] = $stats->mean;
                        }
                    }
                    $stats = null;
                }
            }

            $this->arr_ranks[$k] = $i;

            $prev = $c;
            $i++;
        }


        if(!is_null($stats)){
            foreach($this->arr_ranks as $ri => $rv){
                if(in_array($rv, $stats->array)){
                    $this->arr_ranks[$ri] = $stats->mean;
                }
            }
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

    public function sum($int_sample)
    {
        if(!in_array($int_sample, array(1, 2))){
            throw new \InvalidArgumentException(
                'Sample number for Wilcoxon-Mann-Whitney Test must be 1 or 2!'
            );
        }

        $this->compute();

        return $this->arr_rank_sums[$int_sample - 1];
    }

    public function sigma($int_sample = null)
    {
        if(!is_null($int_sample)){
            if(!in_array($int_sample, array(1, 2))){
                throw new \InvalidArgumentException(
                    'Sample number for Wilcoxon-Mann-Whitney Test must be 1 or 2!'
                );
            }
        }

        $this->compute();

        if(is_null($int_sample)){
            return $this->sigma;
        } else {
            $str = 'sigma' . $int_sample;
            return $this->$str;
        }
    }


    public function mean($int_sample = null)
    {
        if(!is_null($int_sample)){
            if(!in_array($int_sample, array(1, 2))){
                throw new \InvalidArgumentException(
                    'Sample number for Wilcoxon-Mann-Whitney Test must be 1 or 2!'
                );
            }
        }

        $this->compute();

        if(is_null($int_sample)){
            return $this->mean;
        } else {
            return $this->arr_rank_means[$int_sample - 1];
        }
    }



    public function sigma2()
    {
        $this->compute();

        return $this->sigma2;
    }


    public function z()
    {
        $this->compute();

        return $this->z;
    }


    public function z_corrected()
    {
        $this->compute();

        return $this->z_corrected;
    }
}
