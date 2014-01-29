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
    const ALGEBRAIC = '1';
    const TRIGONOMETRIC = '2';

    protected $float_r = 0;
    protected $float_i = 0;
    protected $original = null;



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

        if($name == 'rho')
        {
            return $this->modulus();
        }

        if($name == 'theta')
        {
            return $this->argument();
        }
    }

    /**
     * Creates new Complex number object giving its real and imaginary parts or 
     * rho and theta.
     * 
     * For trigonometric form, the second argument can be a Malenki\Math\Angle 
     * object, and then, the third argument is useless.
     *
     * @param mixed $float_a Number for the real part if type is algebraic, rho otherwise.
     * @param mixed $mix_b Number for the imaginary part if type is algebraic, theta otherwise. Can be an Angle object
     * @param integer $int_type Either Complex::ALGEBRAIC (default) or Complex::TRIGONOMETRIC 
     * @param integer $int_precision Round precision, deafult fixed at 5.
     * @access public
     * @return Complex
     */
    public function __construct($float_a, $mix_b, $int_type = self::ALGEBRAIC, $int_precision = 5)
    {
        if($mix_b instanceof Angle)
        {
            $int_type = self::TRIGONOMETRIC;
        }

        if($int_type == self::ALGEBRAIC)
        {
            if(!is_numeric($float_a) || !is_numeric($mix_b))
            {
                throw new \InvalidArgumentException('Real and Imaginary parts must be valid real numbers.');
            }
            
            $this->float_r = $float_a;
            $this->float_i = $mix_b;
        }
        else
        {
            if(!is_numeric($float_a))
            {
                throw new \InvalidArgumentException('Rho must be valid real numbers.');
            }

            if($float_a < 0)
            {
                throw new \InvalidArgumentException('Rho must be positive or null.');
            }

            if(!(is_numeric($mix_b)||$mix_b instanceof Angle))
            {
                throw new \InvalidArgumentException('Theta must be valid real numbers or an instance of Angle class.');
            }

            if($mix_b instanceof Angle)
            {
                $mix_b = $mix_b->rad;
            }
            
            $this->float_r = round($float_a * cos($mix_b), $int_precision);
            $this->float_i = round($float_a * sin($mix_b), $int_precision);

            $this->original = new \stdClass();
            $this->original->rho = $float_a;
            $this->original->theta = $mix_b;
        }

    }


    /**
     * Computes and returns the norm. 
     * 
     * @access public
     * @return float
     */
    public function norm()
    {
        if($this->original)
        {
            return $this->original->rho;
        }

        return sqrt(pow($this->float_r, 2) + pow($this->float_i, 2));
    }

    public function modulus()
    {
        return $this->norm();
    }

    /**
     * Computes and returns the argument. 
     * 
     * @access public
     * @return float
     */
    public function argument()
    {
        if($this->original)
        {
            return $this->original->theta;
        }

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
     * @param mixed $z Complex or real number 
     * @access public
     * @return Complex
     */
    public function add($z)
    {
        if(is_numeric($z))
        {
            $z = new self($z, 0);
        }
        return new self($this->float_r + $z->re, $this->float_i + $z->im);
    }

    /**
     * Substracts complex number `z` from the current one.
     * 
     * @param mixed $z  Complex or real number
     * @access public
     * @return Complex
     */
    public function substract($z)
    {
        if(is_numeric($z))
        {
            $z = new self($z, 0);
        }
        return $this->add($z->negative());
    }


    
    /**
     * Multiplies current complex number with another.
     * 
     * @param mixed $z  Complex or real number
     * @access public
     * @return Complex
     */
    public function multiply($z)
    {
        if(is_numeric($z))
        {
            $z = new self($z, 0);
        }
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
     * @param mixed $z Complex or real number
     * @access public
     * @return boolean
     */
    public function equal($z)
    {
        if(is_numeric($z))
        {
            $z = new self($z, 0);
        }
        return ($z->real == $this->float_r) && ($z->imaginary == $this->float_i);
    }


    /**
     * In string context, display complex number into the form `a+ib` if it has 
     * been instanciated using algebraic form or into the form 'ρ(cosθ+isinθ)' 
     * for the trigonometric form. 
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        // if trigo…
        if($this->original)
        {
            if($this->norm() == 0)
            {
                return 0;
            }
            if($this->norm() == 1)
            {
                return sprintf('cos %1$f + i⋅sin %1$f', $this->argument());
            }

            return sprintf('%1$f(cos %2$f + i⋅sin %2$f)', $this->norm(), $this->argument());
        }

        $str_sign = '-';
        $str_re = '';
        $str_im = 'i';

        if($this->float_i > 0)
        {
            $str_sign = '+';
        }
        
        if(abs($this->float_i) > 1)
        {
            $str_im = (string) abs($this->float_i) . 'i';
        }
        
        
        if($this->float_i == 0)
        {
            return (string) $this->float_r;
        }
        
        if($this->float_r == 0)
        {
            if(abs($this->float_i) == 1)
            {
                return $this->float_i == 1 ? 'i' : '-i';
            }
            else
            {
                return (string) $this->float_i . 'i';
            }
        }
        else
        {
            $str_re = (string) $this->float_r;
        }

        $arr = array();
        $arr[] = $str_re;
        $arr[] = $str_im;

        return implode($str_sign, $arr);
    }
}
