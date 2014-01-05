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

class Complex
{
    protected $float_r = 0;
    protected $float_i = 0;

    public static function fromExp()
    {
    }

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

    public function __construct($float_r, $float_i)
    {
        if(!is_numeric($float_r) || !is_numeric($float_i))
        {
            throw new \InvalidArgumentException('Real and Imaginary parts must be valid real numbers.');
        }

        $this->float_r = $float_r;
        $this->float_i = $float_i;
    }


    public function norm()
    {
        return sqrt(pow($this->float_r, 2) + pow($this->float_i, 2));
    }

    public function argument()
    {
        return atan2($this->float_i, $this->float_r);
    }


    public function conjugate()
    {
        return new self($this->float_r, -1 * $this->float_i);
    }



    public function negative()
    {
        return new self(-1 * $this->float_r, -1 * $this->float_i);
    }



    public function __toString()
    {
        $str = '%d%di';

        if($this->float_i < 0)
        {
            $str = '%d-%di';
        }

        return sprintf($str, $this->float_r, $this->float_i);
    }
}
