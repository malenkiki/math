<?php
/*
Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use Malenki\Math\Angle;

class AngleTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateOK()
    {
        $a = new Angle(pi()/2);
        $this->assertTrue($a instanceof Malenki\Math\Angle);
    }


    public function testOutputAsDegreesMinutesSecond()
    {
        $a = new Angle(34.53, Angle::TYPE_DEG);
        $dms = $a->dms();
        $this->assertEquals(34, $dms->d);
        $this->assertEquals(31, $dms->m);
        $this->assertEquals(48, $dms->s);
        $this->assertEquals('34°31′48″', $dms->str);
    }


    public function testCreateFromDegreesToGetOthers()
    {
        $a = new Angle(0, Angle::TYPE_DEG);
        $this->assertEquals(0, $a->deg);
        $this->assertEquals(0, $a->rad);
        $this->assertEquals(0, $a->turn);
        $this->assertEquals(0, $a->gon);

        $a = new Angle(45, Angle::TYPE_DEG);
        $this->assertEquals(45, $a->deg);
        $this->assertEquals(pi() / 4, $a->rad);
        $this->assertEquals(1 / 8, $a->turn);
        $this->assertEquals(50, $a->gon);
        
        $a = new Angle(90, Angle::TYPE_DEG);
        $this->assertEquals(90, $a->deg);
        $this->assertEquals(pi() / 2, $a->rad);
        $this->assertEquals(1 / 4, $a->turn);
        $this->assertEquals(100, $a->gon);
        
        $a = new Angle(180, Angle::TYPE_DEG);
        $this->assertEquals(180, $a->deg);
        $this->assertEquals(pi(), $a->rad);
        $this->assertEquals(1 / 2, $a->turn);
        $this->assertEquals(200, $a->gon);
        
        $a = new Angle(270, Angle::TYPE_DEG);
        $this->assertEquals(270, $a->deg);
        $this->assertEquals(3 * pi() / 2, $a->rad);
        $this->assertEquals(3/4, $a->turn);
        $this->assertEquals(300, $a->gon);
         
        $a = new Angle(360, Angle::TYPE_DEG);
        $this->assertEquals(360, $a->deg);
        $this->assertEquals(2 * pi(), $a->rad);
        $this->assertEquals(1, $a->turn);
        $this->assertEquals(400, $a->gon);
        
        $a = new Angle(720, Angle::TYPE_DEG);
        $this->assertEquals(720, $a->deg);
        $this->assertEquals(4 * pi(), $a->rad);
        $this->assertEquals(2, $a->turn);
        $this->assertEquals(800, $a->gon);
    }
    
    
    public function testCreateFromRadiansToGetOthers()
    {
        $a = new Angle(0, Angle::TYPE_RAD);
        $this->assertEquals(0, $a->deg);
        $this->assertEquals(0, $a->rad);
        $this->assertEquals(0, $a->turn);
        $this->assertEquals(0, $a->gon);

        $a = new Angle(pi() / 4, Angle::TYPE_RAD);
        $this->assertEquals(45, $a->deg);
        $this->assertEquals(pi() / 4, $a->rad);
        $this->assertEquals(1 / 8, $a->turn);
        $this->assertEquals(50, $a->gon);
        
        $a = new Angle(pi() / 2, Angle::TYPE_RAD);
        $this->assertEquals(90, $a->deg);
        $this->assertEquals(pi() / 2, $a->rad);
        $this->assertEquals(1 / 4, $a->turn);
        $this->assertEquals(100, $a->gon);
        
        $a = new Angle(pi(), Angle::TYPE_RAD);
        $this->assertEquals(180, $a->deg);
        $this->assertEquals(pi(), $a->rad);
        $this->assertEquals(1 / 2, $a->turn);
        $this->assertEquals(200, $a->gon);
        
        $a = new Angle(3 * pi() / 2, Angle::TYPE_RAD);
        $this->assertEquals(270, $a->deg);
        $this->assertEquals(3 * pi() / 2, $a->rad);
        $this->assertEquals(3/4, $a->turn);
        $this->assertEquals(300, $a->gon);
         
        $a = new Angle(2 * pi(), Angle::TYPE_RAD);
        $this->assertEquals(360, $a->deg);
        $this->assertEquals(2 * pi(), $a->rad);
        $this->assertEquals(1, $a->turn);
        $this->assertEquals(400, $a->gon);
        
        $a = new Angle(4 * pi(), Angle::TYPE_RAD);
        $this->assertEquals(720, $a->deg);
        $this->assertEquals(4 * pi(), $a->rad);
        $this->assertEquals(2, $a->turn);
        $this->assertEquals(800, $a->gon);
    }
}
