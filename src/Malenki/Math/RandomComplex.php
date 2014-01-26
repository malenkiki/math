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

        $frac_min = abs($float_min - $whole_min);
        $frac_max = abs($float_max - $whole_max);

        if($whole_min == $whole_max)
        {
            $rand_frac = new Random();
            
            do
            {
                $out_frac = $rand_frac->get();
            }
            while($out_frac > $frac_max || $out_frac < $frac_min);

            return $whole_min + $out_frac;
        }
        $rand_whole = new Random($whole_min, $whole_max);
        $rand_frac = new Random();

        $out_whole = $rand_whole->get();

        if(!in_array($out_whole, array($whole_min, $whole_max)))
        {
            return $out_whole + $rand_frac->get();
        }
        else
        {


            if($out_whole == $whole_min)
            {
                do
                {
                    $out_frac_min = $rand_frac->get();
                }
                while($out_frac_min < $frac_min);

                if($float_min < 0)
                {
                    return $out_whole + (1 - $out_frac_min);
                }
                else
                {
                    return $out_whole + $out_frac_min;
                }
            }
            else
            {
                if($float_max < 0)
                {
                    do
                    {
                        $out_frac_max = $rand_frac->get();
                    }
                    while($out_frac_max < $frac_max);

                    //TODO WTF??? Sometimes it is not the right value!!!
                    return $out_whole - $out_frac_max;
                }
                else
                {
                    do
                    {
                        $out_frac_max = $rand_frac->get();
                    }
                    while($out_frac_max > $frac_max);

                    return $out_whole + $out_frac_max;
                }
            }
        }
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
        if($this->r && !$this->i && !$this->rho && !$this->theta)
        {
            return new Complex($this->random($this->r->min, $this->r->max), 0);
        }
        if(!$this->r && $this->i && !$this->rho && !$this->theta)
        {
            return new Complex(0, $this->random($this->i->min, $this->i->max));
        }
    }



    public function getMany($n)
    {
        if(!is_integer($n) || $n < 2)
        {
            throw new \InvalidArgumentException('You must take 2 or more items in this case.');
        }

        $arr_out = array();

        for($i = 0; $i < $n; $i++)
        {
            $arr_out[] = $this->get();
        }

        return $arr_out;
    }
}
