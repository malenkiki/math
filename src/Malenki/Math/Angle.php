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
 * Angle 
 * 
 * @property-read $rad Get radians
 * @property-read $deg Get degrees
 * @property-read $gon Get gon
 * @property-read $turn Get number of turns
 * @property-read $dms Get Degrees/Minutes/Second object
 * @todo use http://en.wikipedia.org/wiki/Angle to have other ideas of units. 
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Angle
{
    const TYPE_RAD = 'rad';
    const TYPE_DEG = 'deg';
    const TYPE_GON = 'gon';
    const TYPE_TURN = 'trn';

    protected $float_rad = 0;
    protected $original = null;



    public function __get($name)
    {
        if(in_array($name, array('rad', 'deg', 'gon', 'turn', 'dms')))
        {
            return $this->$name();
        }
    }



    public function __construct($float_angle, $str_type = self::TYPE_RAD)
    {
        $this->original = new \stdClass();
        $this->original->value = $float_angle;
        $this->original->type = $str_type;

        if($str_type == self::TYPE_DEG)
        {
            $this->float_rad = deg2rad($float_angle);
        }
        elseif($str_type == self::TYPE_GON)
        {
            $this->float_rad = $float_angle * pi() / 200;
        }
        elseif($str_type == self::TYPE_TURN)
        {
            $this->float_rad = $float_angle * 2 * pi();
        }
        else
        {
            $this->float_rad = $float_angle;
        }
    }


    public function gon()
    {
        if($this->original->type == self::TYPE_GON)
        {
            return $this->original->value;
        }

        return $this->float_rad * 200 / pi();
    }


    public function deg()
    {
        if($this->original->type == self::TYPE_DEG)
        {
            return $this->original->value;
        }

        return rad2deg($this->float_rad);
    }
    
    
    
    public function rad()
    {
        if($this->original->type == self::TYPE_RAD)
        {
            return $this->original->value;
        }

        return $this->float_rad;
    }


    public function get()
    {
        return $this->original->value;
    }


    public function turn()
    {
        if($this->original->type == self::TYPE_TURN)
        {
            return $this->original->value;
        }

        return $this->float_rad / (2 * pi());
    }



    public function dms()
    {
        $float = abs($this->deg());

        function prov($float)
        {
            $whole_part = (integer) $float;
            $fractional_part = $float - $whole_part;

            $out = new \stdClass();
            $out->whole = $whole_part;
            $out->fractional = $fractional_part * 60;
            return $out;
        }

        $prov_1 = prov($float);
        $prov_2 = prov($prov_1->fractional);
        $prov_3 = prov($prov_2->fractional);

        $dms = new \stdClass();
        $dms->d = $prov_1->whole; 
        $dms->m = $prov_2->whole; 
        $dms->s = $prov_3->whole;
        $dms->str = sprintf('%d°%d′%d″', $dms->d, $dms->m, $dms->s);

        return $dms;
    }



    public function isRight()
    {
    }

    public function isStraight()
    {
    }


    public function isPerigon()
    {
    }


    public function isComplementary(Angle $angle)
    {
    }
    
    
    public function isSupplementary(Angle $angle)
    {
    }
}
