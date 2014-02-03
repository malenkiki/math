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

/**
 * Random generator of Complex Number.
 *
 * This class allows you to get one or many complex numbers with real and 
 * imaginary or rho and theta inside given range.
 *
 * So, to get one complex number with its real part into `[2, 6.5]` range and 
 * its imaginary part into `[-2, 5]` range, you must do that:
 *
 *     $rc = new RandomComplex();
 *     $rc->r(2, 6.5)->i(-2, 5)->get();
 *
 * You can do that with trigonometric form too, but now with 10 generated 
 * items:
 *
 *     $rc = new RandomComplex();
 *     $rc->rho(1, 5)->theta(M_PI / 4, M_PI /2)->getMany(10);
 *
 * Beware: you cannot start with algebraic form and finish with trigonometric. 
 * You must rest or instanciate other object.
 * 
 * @property-read $rho Rho min/max object if defined
 * @property-read $theta Theta min/max object if defined
 * @property-read $r Real part min/max object if defined
 * @property-read $i Imaginary part min/max if defined
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class RandomComplex
{
    protected $rho = null;
    protected $theta = null;
    protected $r = null;
    protected $i = null;



    protected static function random($float_min, $float_max)
    {


        if($float_max >= 0)
        {
            $r = new Random();

            while(true)
            {
                $float_prov = $float_max * $r->get();

                if($float_prov >= $float_min)
                {
                    return $float_prov;
                }
            }
        }
        else
        {
            $r = new Random();

            while(true)
            {
                $float_prov = $float_min * $r->get();

                if($float_prov <= $float_max)
                {
                    return $float_prov;
                }
            }
        }
    }


    protected static function checkOrder($float_min, $float_max)
    {
        if(!is_numeric($float_min) && !is_numeric($float_max))
        {
            throw new \InvalidArgumentException('Min and max values must be valid numbers.');
        }

        if($float_min >= $float_max)
        {
            throw new \InvalidArgumentException('Max value must be greater than min value!');
        }
    }



    public function __get($name)
    {
        if(in_array($name, array('rho', 'theta', 'r', 'i')))
        {
            return $this->$name;
        }
    }



    public function rho($float_min, $float_max)
    {
        self::checkOrder($float_min, $float_max);

        if($float_min < 0 || $float_max < 0)
        {
            throw new \InvalidArgumentException('Rho value must be a positive number!');
        }

        if($this->r || $this->i)
        {
            throw new \RuntimeException('You cannot set rho value, because algebraic form is in use.');
        }

        $this->rho = new \stdClass();
        $this->rho->min = $float_min;
        $this->rho->max = $float_max;

        return $this;
    }
    


    public function theta($float_min, $float_max)
    {
        self::checkOrder($float_min, $float_max);

        if($this->r || $this->i)
        {
            throw new \RuntimeException('You cannot set theta value, because algebraic form is in use.');
        }


        $this->theta = new \stdClass();
        $this->theta->min = $float_min;
        $this->theta->max = $float_max;

        return $this;
    }


    
    public function r($float_min, $float_max)
    {
        self::checkOrder($float_min, $float_max);

        if($this->rho || $this->theta)
        {
            throw new \RuntimeException('You cannot set real part because trigonometric form is in use.');
        }

        $this->r = new \stdClass();
        $this->r->min = $float_min;
        $this->r->max = $float_max;

        return $this;
    }
    
    
    
    public function i($float_min, $float_max)
    {
        self::checkOrder($float_min, $float_max);

        if($this->rho || $this->theta)
        {
            throw new \RuntimeException('You cannot set imaginary part because trigonometric form is in use.');
        }


        $this->i = new \stdClass();
        $this->i->min = $float_min;
        $this->i->max = $float_max;

        return $this;
    }



    public function get()
    {
        if($this->r || $this->i)
        {
            if(!is_object($this->i))
            {
                return new Complex(
                    self::random($this->r->min, $this->r->max),
                    0
                );
            }
            
            if(!is_object($this->r))
            {
                return new Complex(
                    0,
                    self::random($this->i->min, $this->i->max)
                );
            }

            return new Complex(
                self::random($this->r->min, $this->r->max),
                self::random($this->i->min, $this->i->max)
            );
        }
        
        if($this->rho || $this->theta)
        {
            if(!is_object($this->theta))
            {
                return new Complex(
                    self::random($this->rho->min, $this->rho->max),
                    0,
                    Complex::TRIGONOMETRIC
                );
            }
            
            if(!is_object($this->rho))
            {
                return new Complex(
                    0,
                    self::random($this->theta->min, $this->theta->max),
                    Complex::TRIGONOMETRIC
                );
            }

            return new Complex(
                self::random($this->rho->min, $this->rho->max),
                self::random($this->theta->min, $this->theta->max),
                Complex::TRIGONOMETRIC
            );
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


    public function reset()
    {
        $this->rho = null;
        $this->theta = null;
        $this->r = null;
        $this->i = null;

        return $this;
    }
}
