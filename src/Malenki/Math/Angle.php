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
 * Angle deals with degrees, radians, grades and turns.
 *
 * By default, radians are used, but you can instanciate angle using unit you want.
 *
 *     $a = new Angle(M_PI / 2); // create angle from radians
 *     $a = new Angle(90, Angle::TYPE_DEG); // degrees
 *
 * As you can see, the second argument is a class constant. There are 4 constants, listed below:
 *
 *  - `TYPE_RAD` for radians,
 *  - `TYPE_DEG` for degrees,
 *  - `TYPE_GON` for grades,
 *  - `TYPE_TURN` for turns.
 *
 * When Angle is created, you can compare it with another to know if they are complementary or supplementary.
 *
 * For current angle, you can convert it to other units.
 *
 * Current angle can also be tested to know if it is right, straight or perigon (complete lap).
 *
 * See methods to know more!
 *
 * @property-read $rad Get radians
 * @property-read $deg Get degrees
 * @property-read $gon Get gon
 * @property-read $turn Get number of turns
 * @property-read $dms Get Degrees/Minutes/Second object
 * @property-read $type Original type used to create Angle, the type returned by get()
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

    /**
     * Radians internal value.
     *
     * @var float
     * @access protected
     */
    protected $float_rad = 0;

    /**
     * Stored original value if other than radians.
     *
     * @var mixed
     * @access protected
     */
    protected $original = null;

    /**
     * Method to have magick getters.
     *
     * @param  string $name
     * @access public
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, array('rad', 'deg', 'gon', 'turn', 'dms'))) {
            return $this->$name();
        }

        if ($name == 'type') {
            return $this->original->type;
        }
    }

    /**
     * Create new Angle, from radians by default, or with second argument, can
     * be degrees, grades or turns too.
     *
     * @param  float  $float_angle Value of the angle, in radians if second argument is not given
     * @param  string $str_type    One of the class' constants. Optional.
     * @access public
     * @return void
     */
    public function __construct($float_angle, $str_type = self::TYPE_RAD)
    {
        $this->original = new \stdClass();
        $this->original->value = $float_angle;
        $this->original->type = $str_type;

        if ($str_type == self::TYPE_DEG) {
            $this->float_rad = deg2rad($float_angle);
        } elseif ($str_type == self::TYPE_GON) {
            $this->float_rad = $float_angle * pi() / 200;
        } elseif ($str_type == self::TYPE_TURN) {
            $this->float_rad = $float_angle * 2 * pi();
        } else {
            $this->float_rad = $float_angle;
        }
    }



    /**
     * Gets angle's value as gon (grade).
     *
     * @access public
     * @return float
     */
    public function gon()
    {
        if ($this->original->type == self::TYPE_GON) {
            return $this->original->value;
        }

        return $this->float_rad * 200 / pi();
    }

    /**
     * Gets angle's value as degrees.
     *
     * @access public
     * @return float
     */
    public function deg()
    {
        if ($this->original->type == self::TYPE_DEG) {
            return $this->original->value;
        }

        return rad2deg($this->float_rad);
    }



    /**
     * gets angles as radians
     *
     * @access public
     * @return float
     */
    public function rad()
    {
        if ($this->original->type == self::TYPE_RAD) {
            return $this->original->value;
        }

        return $this->float_rad;
    }



    /**
     * Gets angle's value, as it was given at instenciation time.
     *
     * @access public
     * @return float
     */
    public function get()
    {
        return $this->original->value;
    }

    /**
     * Gets number of turns
     *
     * @access public
     * @return float
     */
    public function turn()
    {
        if ($this->original->type == self::TYPE_TURN) {
            return $this->original->value;
        }

        return $this->float_rad / (2 * pi());
    }

    /**
     * Gets angle as a "Degrees/Minutes/seconds" object.
     *
     * DMS object returned as 4 attributes:
     *
     *  - `d` for degrees,
     *  - `m` for minutes,
     *  - `s` for seconds,
     *  - `str` for the DMS string (`34°56′23″`)
     *
     * @access public
     * @return stdClass
     */
    public function dms()
    {
        $float = abs($this->deg());

        $prov = function ($float) {
            $whole_part = (integer) $float;
            $fractional_part = $float - $whole_part;

            $out = new \stdClass();
            $out->whole = $whole_part;
            $out->fractional = $fractional_part * 60;

            return $out;
        };

        $prov_1 = $prov($float);
        $prov_2 = $prov($prov_1->fractional);
        $prov_3 = $prov($prov_2->fractional);

        $dms = new \stdClass();
        $dms->d = $prov_1->whole;
        $dms->m = $prov_2->whole;
        $dms->s = $prov_3->whole;
        $dms->str = sprintf('%d°%d′%d″', $dms->d, $dms->m, $dms->s);

        return $dms;
    }

    /**
     * Checks whether current angle is right (90°), even if there are several turns.
     *
     * @access public
     * @return boolean
     */
    public function isRight()
    {
        return in_array(abs($this->turn()) - (integer) abs($this->turn()), array(1/4, 3/4));
    }

    /**
     * Checks whether current angle is straight (180°), even if there are several turns.
     *
     * @access public
     * @return boolean
     */
    public function isStraight()
    {
        return abs($this->turn()) - (integer) abs($this->turn()) == 0.5;
    }

    /**
     * Checks whether angle does at least one complete turn (360° × n).
     *
     * @access public
     * @return boolean
     */
    public function isPerigon()
    {
        return fmod(abs($this->turn()), 1) == 0;
    }

    /**
     * Tests current angle with another to know if there are complementary together
     *
     * @param  Angle   $angle
     * @access public
     * @return boolean
     */
    public function isComplementary(Angle $angle)
    {
        $out = new self($this->float_rad + $angle->rad);

        return $out->isRight();
    }

    /**
     * Test if current angle is supplementary with another.
     *
     * @param  Angle   $angle
     * @access public
     * @return boolean
     */
    public function isSupplementary(Angle $angle)
    {
        $out = new self($this->float_rad + $angle->rad);

        return $out->isStraight();
    }
}
