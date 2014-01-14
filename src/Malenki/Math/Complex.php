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
 * Complex number
 *
 * Complex numbers are defined by default using real part `a` and imaginary 
 * part `bi` like that: `a+bi`.
 *
 * But this class allows you to use polar form too, using `rho` and `theta` 
 * values (length and angle).
 * 
 * @property-read float $real The real part
 * @property-read float $re 2nd way to get real part
 * @property-read float $r 3rd way to get real part
 * @property-read float $imaginary The imaginary part
 * @property-read float $im 2nd way to get imaginary part
 * @property-read float $i 3rd way to get imaginary part
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Complex
{
    protected $float_r = 0;
    protected $float_i = 0;

    public static function fromExp()
    {
    }

    /**
     * Creates complex number using `rho` and `theta` values instead using real 
     * and imaginary part. 
     * 
     * @param mixed $rho 
     * @param mixed $theta 
     * @static
     * @access public
     * @return Complex
     */
    public static function fromPolar($rho, $theta)
    {
        return new self($rho * cos($theta), $rho * sin($theta));
    }


    public function __get($name)
    {
        if(in_array($name, array('real', 'r', 're')))
        {
            return $this->float_r;
        }
        
        if(in_array($name, array('imaginary', 'i', 'im')))
        {
            return $this->float_i;
        }
    }

    /**
     * Creates new Complex number object giving its real and imaginary parts.
     * 
     * @param mixed $float_r Number for the real part
     * @param mixed $float_i Number for the imaginary part
     * @access public
     * @return Complex
     */
    public function __construct($float_r, $float_i)
    {
        if(!is_numeric($float_r) || !is_numeric($float_i))
        {
            throw new \InvalidArgumentException('Real and Imaginary parts must be valid real numbers.');
        }

        $this->float_r = $float_r;
        $this->float_i = $float_i;
    }


    /**
     * Computes and returns the norm. 
     * 
     * @access public
     * @return float
     */
    public function norm()
    {
        return sqrt(pow($this->float_r, 2) + pow($this->float_i, 2));
    }

    /**
     * Computes and returns the argument. 
     * 
     * @access public
     * @return float
     */
    public function argument()
    {
        return atan2($this->float_i, $this->float_r);
    }



    /**
     * Return conjugate complex number for the current one.
     * 
     * @access public
     * @return Complex
     */
    public function conjugate()
    {
        return new self($this->float_r, -1 * $this->float_i);
    }



    /**
     * Gives the negative Complex number of the current one.
     * 
     * @access public
     * @return Complex
     */
    public function negative()
    {
        return new self(-1 * $this->float_r, -1 * $this->float_i);
    }



    /**
     * Add value to the current complex number, creating new complex number.
     *
     * @todo Allow non complex number too 
     * @param mixed $z 
     * @access public
     * @return Complex
     */
    public function add($z)
    {
        return new self($this->float_r + $z->re, $this->float_i, $z->im);
    }

    /**
     * Substracts complex number `z` from the current one.
     * 
     * @todo Allow non complex number too 
     * @param mixed $z 
     * @access public
     * @return Complex
     */
    public function substract($z)
    {
        return $this->add($z->negative());
    }


    
    /**
     * Multiplicates current complex number with another one.
     * 
     * @todo Allow real numbers too
     * @param mixed $z 
     * @access public
     * @return Complex
     */
    public function multiplicate($z)
    {
        return new self(
            ($this->float_r * $z->re) - ($this->float_i * $z->im),
            ($this->float_r * $z->im) + ($z->re * $this->float_i)
        );
    }



    public function divide($z)
    {
    }


   
    /**
     * Tests whether given `z` complex number is equal to the current complex 
     * number.
     * 
     * @todo Allow non complex number too 
     * @param Complex $z 
     * @access public
     * @return boolean
     */
    public function equal(Complex $z)
    {
        return ($z->real == $this->float_r) && ($z->imaginary == $this->float_i);
    }


    /**
     * In string context, display complex number into the form `a+ib`. 
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        $str = '%d%di';

        if($this->float_i > 0)
        {
            $str = '%d+%di';
        }
        
        if($this->float_i == 0)
        {
            return (string) $this->float_r;
        }

        return sprintf($str, $this->float_r, $this->float_i);
    }
}
