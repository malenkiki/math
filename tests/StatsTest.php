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

use Malenki\Math\Stats;

class StatsTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithoutArgShouldSuccess()
    {
        $s = new Stats();
        $this->assertInstanceOf('Malenki\Math\Stats', $s);
    }

    public function testInstanciateWithArgShouldSuccess()
    {
        $s = new Stats(array(1,4,7,5,8));
        $this->assertInstanceOf('Malenki\Math\Stats', $s);
    }

    public function testInstanciateWithArrayHavingBadValueTypeMustFail()
    {
        $this->markTestIncomplete();
    }


    public function testCountingValuesInsideCollectionShouldSuccess()
    {
        $s = new Stats();
        $this->assertCount(0, $s);
        $s->merge(array(3,8,4,2,6,3,8,5,4,3,3,7,8,4,8,1));
        $this->assertCount(16, $s);
        $s = new Stats(array(1,4,7,5,8));
        $this->assertCount(5, $s);
    }


    public function testComputingArithmeticMeanShouldSuccess()
    {
        $s = new Stats(array(1, 2, 3, 4));
        $this->assertEquals(2.5, $s->arithmeticMean());
        $this->assertEquals(2.5, $s->arithmetic_mean);
        $this->assertEquals(2.5, $s->mean());
        $this->assertEquals(2.5, $s->mean);
        
        $s = new Stats(array(1, 2, 4, 8, 16));
        $this->assertEquals(6.2, $s->arithmeticMean());
        $this->assertEquals(6.2, $s->arithmetic_mean);
        $this->assertEquals(6.2, $s->mean());
        $this->assertEquals(6.2, $s->mean);
    }


    public function testComputeHarmonicMeanShouldSuccess()
    {
        $s = new Stats(array(1, 2, 4));
        $this->assertEquals(12/7, $s->harmonicMean());
        $this->assertEquals(12/7, $s->harmonic_mean);
        $this->assertEquals(12/7, $s->subcontrary_mean);
        $this->assertEquals(12/7, $s->H);
    }

    public function testComputeGeometricMeanShouldSuccess()
    {
        $s = new Stats(array(4, 1, 1/32));
        $this->assertEquals(1/2, $s->geometricMean());
        $this->assertEquals(1/2, $s->geometric_mean);
        $this->assertEquals(1/2, $s->G);
        
        $s = new Stats(array(2, 8));
        $this->assertEquals(4, $s->geometricMean());
        $this->assertEquals(4, $s->geometric_mean);
        $this->assertEquals(4, $s->G);
    }

    public function testComputingQuadraticMeanShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testComputingGeneralizedMeanShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testComputingGeneralizedMeanWithPEqualdZeroShouldFail()
    {
        $this->markTestIncomplete();
    }

    public function testComputingGeneralizedMeanWithPNegativeShouldFail()
    {
        $this->markTestIncomplete();
    }

    public function testComputingGeneralizedMeanWithCollectionHavingNegativeNumbersShouldFail()
    {
        $this->markTestIncomplete();
    }

    public function testGettingRangeShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGettingVarianceShouldSuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGettingStandardDeviationShouldSuccess()
    {
        $this->markTestIncomplete();
    }
}
