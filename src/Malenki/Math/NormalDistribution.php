<?php
/*
 * Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>
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
 * Normal Distribution.
 *
 * Normal distribution is statistical mathematical function 
 * defined by mean (µ) and standard deviation (𝛔).
 *
 * This class used curve created with given mean and sigma, can return value 
 * for given x, but can also create fictive samples too.
 * 
 * @property-read $mean Gets the mean
 * @property-read $mu Gets the mean too
 * @property-read $µ Last attribute to get mean
 * @property-read $sigma Gets standard deviation
 * @property-read $std This gets standard deviation too
 * @property-read $𝛔 Last way to get standard deviation
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class NormalDistribution
{
    /**
     * Stores sigma value, the standard deviation. 
     * 
     * @var float
     * @access protected
     */
    protected $float_sigma = 0;
    
    /**
     * Stores mu value, the mean. 
     * 
     * @var float
     * @access protected
     */
    protected $float_mu = 0;

    /**
     * Precision for number of digits into the mantis. 
     * 
     * @var float
     * @access protected
     */
    protected $int_precision = 0;



    public function __get($name)
    {
        if(in_array($name, array('mean', 'mu', 'µ')))
        {
            return $this->float_mu;
        }
        
        if(in_array($name, array('sigma', 'std', '𝛔')))
        {
            return $this->float_sigma;
        }
    }



    /**
     * Create normal distribution object with given mean and sigma.
     *
     * To create fictive samples or just study curve, only mean and sigma are 
     * necessary for the first step.
     * 
     * @throw \InvalidArgumentException If sigma or mu are not number
     * @throw \InvalidArgumentException If mu is not a positive number
     * @param float $mu Mean 
     * @param float $sigma Standard deviation
     * @access public
     * @return void
     */
    public function __construct($mu = 0, $sigma = 1)
    {
        if(!is_numeric($sigma) || !is_numeric($mu))
        {
            throw new \InvalidArgumentException('Sigma and Mu must be number.');
        }

        if($mu < 0)
        {
            throw new \InvalidArgumentException('µ must be a positive real number.');
        }

        $this->float_sigma = $sigma;
        $this->float_mu = $mu;
    }



    /**
     * Compute the variance and return the result.
     * 
     * @see NormalDistribution::precision()
     * @access public
     * @return float
     */
    public function variance()
    {
        $float_variance = pow($this->float_sigma, 2);
        
        if($this->int_precision)
        {
            return round($float_variance, $this->int_precision);
        }

        return $float_variance;
    }



    /**
     * Set precision for all returned results.
     * 
     * @param integer $n Give number of digits for the mantis
     * @access public
     * @return void
     */
    public function precision($n)
    {
        if(!is_numeric($n) || $n < 0)
        {
            throw new \InvalidArgumentException('Precision must be positive number');
        }

        $this->int_precision = (integer) $n;
    }



    /**
     * Give the maximal value of the curve for the current sigma and mu. 
     * 
     * @see NormalDistribution::precision()
     * @access public
     * @return float
     */
    public function max()
    {
        $float_max = 1 / ($this->float_sigma * sqrt(2 * pi()));

        if($this->int_precision)
        {
            return round($float_max, $this->int_precision);
        }

        return $float_max;
    }



    /**
     * Gets the full width at half maximum. 
     * 
     * @see NormalDistribution::precision()
     * @access public
     * @return float
     */
    public function fwhm()
    {
        $float_fwhm = 2 * sqrt(2 * log(2)) * $this->float_sigma;

        if($this->int_precision)
        {
            return round($float_fwhm, $this->int_precision);
        }

        return $float_fwhm;
    }



    /**
     * Function that returns normal distribution value for given x. 
     * 
     * @see NormalDistribution::precision()
     * @throw \InvalidArgumentException If x is not numerical value.
     * @param float $x 
     * @access public
     * @return float
     */
    public function f($x)
    {
        if(!is_numeric($x))
        {
            throw new \InvalidArgumentException('x variable must be numeric value.');
        }

        $float_fx = exp(-0.5 * pow(($x - $this->float_mu) / $this->float_sigma, 2)) / ($this->float_sigma * sqrt(2 * pi()));

        if($this->int_precision)
        {
            return round($float_fx, $this->int_precision);
        }

        return $float_fx;
    }


    /**
     * Do same things like NormalDistribution::f() but for several values at once. 
     * 
     * @see NormalDistribution::precision()
     * @param array $arr_x Several x values
     * @access public
     * @return array Each items is small object having x and fx attributes.
     */
    public function fn(array &$arr_x)
    {
        $arr = array();

        foreach($arr_x as $x)
        {
            $item = new \stdClass();
            $item->x = $x;
            $item->fx = $this->f($x);
            $arr[] = $item;
        }

        return $arr;
    }


    /**
     * Simulates samples following normal distribution
     * 
     * @see NormalDistribution::precision()
     * @throw \InvalidArgumentException If amount is not greater than zero
     * @param integer $amount 
     * @access public
     * @return array
     */
    public function samples($amount)
    {

        if(!is_numeric($amount) || $amount < 1)
        {
            throw new \InvalidArgumentException('Amount of samples must be greater or equal to one');
        }

        $arr = array();

        for($i = 1; $i <= $amount; $i++)
        {
            $r = new Random();
            $float_u = $r->get();
            $float_v = $r->get();

            $double_x = $this->float_sigma * sqrt(-2 * log($float_u)) * cos(2 * pi() * $float_v) + $this->float_mu;

            if($this->int_precision)
            {
                $arr[] = round($double_x, $this->int_precision);
            }
            else
            {
                $arr[] = $double_x;
            }
        }

        return $arr;
    }
}
