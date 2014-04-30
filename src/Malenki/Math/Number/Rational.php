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
use \Malenki\Math\Number\Integer;

class Rational
{
    protected $int_numerator = null;
    protected $int_denominator = null;

    public function __get($name)
    {
        if(in_array($name, array('numerator', 'denominator'))){
            $prop = 'int_' . $name;
            return new Integer($this->$prop);
        }
    }

    public function __construct($numerator, $denominator)
    {
        if(!is_integer($numerator) || !is_integer($denominator)){
            throw new \InvalidArgumentException('Denominator and nominator must be integers!');
        }

        if($denominator == 0){
            throw new \InvalidArgumentException('Denominator cannot be zero!');
        }

        $this->int_numerator = $numerator;
        $this->int_denominator = $denominator;
    }

    public function __toString()
    {
        return sprintf('%d/%d', $this->int_numerator, $this->int_denominator);
    }
}
