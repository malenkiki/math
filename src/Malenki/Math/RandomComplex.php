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

class RandomComplex
{
    protected $rho = null;
    protected $theta = null;
    protected $r = null;
    protected $i = null;



    protected function random($float_min, $float_max)
    {
        $whole_min = (integer) $float_min;
        $whole_max = (integer) $float_max;

        $frac_min = $float_min - $whole_min;
        $frac_max = $float_max - $whole_max;

        $rand_whole = new Random($whole_min, $whole_max);
        $rand_frac = new Random();

        $rand_frac->get() / abs($frac_min);
        $rand_frac->get() / abs($frac_max);
    }



    public function rho($float_min, $float_max)
    {
        $this->rho = new \stdClass();
        $this->rho->min = $float_min;
        $this->rho->max = $float_max;

        return $this;
    }
    


    public function theta($float_min, $float_max)
    {
        $this->theta = new \stdClass();
        $this->theta->min = $float_min;
        $this->theta->max = $float_max;

        return $this;
    }


    
    public function r($float_min, $float_max)
    {
        $this->r = new \stdClass();
        $this->r->min = $float_min;
        $this->r->max = $float_max;

        return $this;
    }
    
    
    
    public function i($float_min, $float_max)
    {
        $this->i = new \stdClass();
        $this->i->min = $float_min;
        $this->i->max = $float_max;

        return $this;
    }



    public function get()
    {
    }



    public function getMany($n)
    {
    }
}
