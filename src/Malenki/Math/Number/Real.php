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

namespace Malenki\Math\Number;

class Real
{
    const PI = M_PI;
    const E = M_E;
    const EULER = M_EULER;
    const SQRT2 = M_SQRT2;

    protected $value = 0.0;

    public function __get($name)
    {
        if($name == 'pi'){
            return new self(self::PI);
        }
        
        if($name == 'e'){
            return new self(self::E);
        }
        
        if($name == 'euler'){
            return new self(self::EULER);
        }
    }


    public function __construct($num)
    {
        if(is_numeric($num)){
            $this->value = (double) $num;
        }
    }

    public function isZero()
    {
        return $this->value == 0;
    }

    public function decimal()
    {
        $sign = $this->value < 0 ? -1 : 1;

        return new self($sign * (abs($this->value) - floor(abs($this->value))));
    }

    public function hasDecimal()
    {
        return !$this->decimal()->isZero();
    }

    public function isInteger()
    {
        return !$this->hasDecimal();
    }
}
