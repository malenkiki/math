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

class WilcoxonMannWhitney
{
    protected $arr_samples = array();
    protected $arr_ranks = array();
    protected $arr_rank_samples = array();
    protected $arr_rank_values = array();
    protected $u1 = null;
    protected $u2 = null;
    protected $u = null;
    protected $sigma = null;
    protected $mean = null;
    protected $m1 = null;
    protected $m2 = null;
    protected $sigma1 = null;
    protected $sigma2 = null;
    protected $r1 = null;
    protected $r2 = null;


    public function __get($name)
    {
        if(in_array($name, array('u1','u2','u', 'sigma', 'mean'))){
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


    protected function compute()
    {
        if(is_null($this->u1) || is_null($this->u2)){
            $this->computeRanks();

            $s1 = new \Malenki\Math\Stats\Stats($this->arr_ranks[0]);
            $s2 = new \Malenki\Math\Stats\Stats($this->arr_ranks[1]);

            $n1 = count($this->arr_samples[0]);
            $n2 = count($this->arr_samples[1]);

            $r1 = $s1->sum;
            $r2 = $s2->sum;
            
            $m1 = $s1->mean;
            $m2 = $s2->mean;

            $this->u1 =  $n1 * $n2 + ( 0.5 * $n1 * ($n1 + 1)) - $r1;
            $this->u2 =  $n1 * $n2 + ( 0.5 * $n2 * ($n2 + 1)) - $r2;
            $this->u = min($this->u1(), $this->u2());
            $this->mean = 0.5 * $n1 * $n2;
            $this->sigma = sqrt($n1 * $n2 * ($n1 + $n2 + 1) / 12);

            $this->r1 = $r1;
            $this->r2 = $r2;

            $this->m1 = $m1;
            $this->m2 = $m2;

            $this->sigma1 = $s1->sigma;
            $this->sigma2 = $s2->sigma;
        }
    }

    /**
     * @todo
     */
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

        $str = 'r' . $int_sample;
        return $this->$str;
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
            $str = 'm' . $int_sample;
            return $this->$str;
        }
    }

}
