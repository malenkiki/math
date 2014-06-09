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

use Malenki\Math\Stats\ParametricTest\TTest\Independant;
use Malenki\Math\Stats\Stats;

class IndependantTTestOfStudentTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateStudentIndependantsShouldSuccess()
    {
        $t = new Independant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Independant',
            $t
        );
    }

    public function testAddingTwoSamplesShouldSuccess()
    {
        $t = new Independant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Independant',
            $t->add(new Stats(array(6, 3, 4)))
        );
        
        $t = new Independant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Independant',
            $t->add(array(6, 3, 4))->add(array(7, 5, 9))
        );
        
        
        $t = new Independant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Independant',
            $t->set(array(6, 3, 4), array(7, 5, 9))
        );
        
        $t = new Independant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Independant',
            $t->set(new Stats(array(6, 3, 4)), new Stats(array(7, 5, 9)))
        );
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddingMoreThanTwoSamplesShouldFail()
    {
        $t = new Independant();
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
    }

    public function testGettingMeanShouldSuccess()
    {
        $t = new Independant();
        $t->add(array(102, 97, 104, 91, 104, 108, 93, 94, 101, 100, 89, 108, 110, 96, 99, 98, 101, 88, 104, 98, 107, 102, 104, 90, 116, 95, 99, 102, 104, 93, 100, 87));
        $t->add(array(99, 104, 105, 95, 98, 102, 115, 102, 97, 91, 106, 108, 101, 100, 96, 95, 93, 104, 100, 107, 109, 109, 95, 97, 102, 99, 102, 104, 108, 87));
        $this->assertEquals(-1.5, $t->mean());
    }
    
    public function testGettingSigma2ShouldSuccess()
    {
        $t = new Independant();
        $t->add(array(102, 97, 104, 91, 104, 108, 93, 94, 101, 100, 89, 108, 110, 96, 99, 98, 101, 88, 104, 98, 107, 102, 104, 90, 116, 95, 99, 102, 104, 93, 100, 87));
        $t->add(array(99, 104, 105, 95, 98, 102, 115, 102, 97, 91, 106, 108, 101, 100, 96, 95, 93, 104, 100, 107, 109, 109, 95, 97, 102, 99, 102, 104, 108, 87));
        $this->assertEquals(41.7, round($t->sigma2(), 1));
    }

    public function testGettingStandardDeviationShouldSuccess()
    {
        $t = new Independant();
        $t->add(array(102, 97, 104, 91, 104, 108, 93, 94, 101, 100, 89, 108, 110, 96, 99, 98, 101, 88, 104, 98, 107, 102, 104, 90, 116, 95, 99, 102, 104, 93, 100, 87));
        $t->add(array(99, 104, 105, 95, 98, 102, 115, 102, 97, 91, 106, 108, 101, 100, 96, 95, 93, 104, 100, 107, 109, 109, 95, 97, 102, 99, 102, 104, 108, 87));
        $this->markTestIncomplete();
        //$this->assertEquals();
    }
}
