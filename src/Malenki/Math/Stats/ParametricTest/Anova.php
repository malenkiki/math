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

class Anova implements \Countable
{
    protected $arr_samples = array();
    protected $int_dof = null;
    protected $int_wgdof = null;
    protected $float_f_ratio = null;


    public function __get($name)
    {
        if(in_array($name, array('degrees_of_freedom', 'dof'))){
            return $this->dof();
        }
        
        if(in_array($name, array('within_group_degrees_of_freedom', 'wgdof'))){
            return $this->wgdof();
        }

        if(in_array($name, array('f_ratio', 'f'))){
            return $this->fRatio();
        }
    }

    public function add($s)
    {
        if(is_array($s)){
            $s = new Stats($s);
        } elseif(!($s instanceof Stats))
        {
            throw new \InvalidArgumentException(
                'Added sample to ANOVA test must be array or Stats instance'
            );
        }
        $this->arr_samples[] = $s;
        $this->clear();
    }

    protected function clear()
    {
        $this->int_dof = null;
        $this->int_wgdof = null;
        $this->float_f_ratio = null;
    }

    public function count()
    {
        return count($this->arr_samples);
    }

    public function degreesOfFreedom()
    {
        if(is_null($this->int_dof)){
            $this->int_dof = count($this->arr_samples) - 1;
        }

        return $this->int_dof;
    }

    public function dof()
    {
        return $this->degreesOfFreedom();
    }

    public function withinGroupDegreesOfFreedom()
    {
        if(is_null($this->int_wgdof)){
            $arr = array();

            foreach($this->arr_samples as $s){
                $arr[] = count($s) - 1;
            }

            $this->int_wgdof = array_sum($arr);
        }

        return $this->int_wgdof;
    }

    public function wgdof()
    {
        return $this->withinGroupDegreesOfFreedom();
    }

    protected function compute()
    {
        if(is_null($this->float_f_ratio)){
            $overall = new Stats();

            foreach($this->arr_samples as $s){
                $overall->add($s->mean);
            }

            $between_grp_sum2 = 0;
            $within_grp_sum2 = 0;

            for($i = 0; $i < count($overall); $i++){
                $s = $this->arr_samples[$i];
                $between_grp_sum2 += count($s) * pow($s->mean - $overall->mean, 2);
                $s_centered = new Stats($s->center);
                $within_grp_sum2 += $s_centered->square_sum;
            }

            $between_grp_sum2_mean = $between_grp_sum2 / $this->dof();
            $within_grp_sum2_mean = $within_grp_sum2 / $this->wgdof();

            $this->float_f_ratio = $between_grp_sum2_mean / $within_grp_sum2_mean;
        }

        return $this->float_f_ratio;
    }

    public function fRatio()
    {
        return $this->compute();
    }

    public function f()
    {
        return $this->fRatio();
    }
}
