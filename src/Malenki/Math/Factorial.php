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
 * Compute factorial.
 *
 * Very easy to use, instanciate it with `n` rank and then get value by calling 
 * `result` attribute.
 *
 *     $f = new Factorial(5);
 *     $f->result; // should be 120
 *     $f->n; // you can get rank as reminder too.
 * 
 * @property-read $n Gets the rank
 * @property-read $result Gets the result
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Factorial
{
    /**
     * Rank n for the factorial 
     * 
     * @var integer
     * @access protected
     */
    protected $int_n = null;


    /**
     * Result for the factorial n 
     * 
     * @var integer
     * @access protected
     */
    protected $int_result = null;



    /**
     * Defines magick getters to have the rank and the result. 
     * 
     * @param string $name 
     * @access public
     * @return integer
     */
    public function __get($name)
    {
        if(in_array($name, array('n', 'result')))
        {
            $str_attribute = 'int_' . $name;
            return $this->$str_attribute;
        }
    }



    /**
     * Creates new factorial of rank n. 
     * 
     * @param integer $n The rank
     * @access public
     * @return void
     */
    public function __construct($n)
    {
        if($n < 0 || !is_integer($n))
        {
            throw new \InvalidArgumentException('Fractional must have positive or null integers');
        }

        $this->int_n = $n;

        if($n == 0)
        {
            $this->int_result = 1;
        }
        else
        {
            $this->int_result = $this->compute($n);
        }
    }



    /**
     * Computes the factorial and returns the result internally.
     * 
     * @param integer $n The rank
     * @access protected
     * @return void
     */
    protected function compute($n)
    {
        $int_fact = 1;

        for($i = 1; $i <= $n ; $i++)
        {
            $int_fact *= $i;
        }

        return $int_fact;
    }



    /**
     * Display the result of the factorial into string context. 
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        return (string) $this->int_result;
    }

}
