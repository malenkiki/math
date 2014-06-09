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

use Malenki\Math\Stats\ParametricTest\TTest\OneSample;
use Malenki\Math\Stats\Stats;

class OneSampleTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateStudentOneSampleShouldSuccess()
    {
        $t = new OneSample();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\OneSample',
            $t
        );
    }
    
    public function testSettingSampleShouldSuccess()
    {
        $t = new OneSample();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\OneSample',
            $t->set(new Stats(array(6, 3, 4, 5, 4, 4, 8, 6)))
        );
    }


    public function testSettingWholePopulationMeanShouldSuccess()
    {
        $t = new OneSample();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\OneSample',
            $t->populationMean(0.5)
        );
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingWholePopulationMeanUsingBadTypeShouldFail()
    {
        $t = new OneSample();
        $t->populationMean(null);
    }

    public function testGettingSigma2ShouldSuccess()
    {
        $t = new OneSample();
        $t
            ->populationMean(0.5)
            ->set(
                array(
                    5, 5.5, 4.5, 5, 5, 6, 5, 5, 4.5, 5, 5, 4.5, 4.5, 5.5, 4, 5, 5, 5.5, 4.5, 5.5, 5, 5.5
                )
            );

        $this->assertEquals(0.214, round($t->sigma2(), 3));
    }
}
