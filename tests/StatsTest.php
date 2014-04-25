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

    /**
     * @expectedException \RuntimeException
     */
    public function testInstanciateWithArrayHavingBadValueTypeMustFail()
    {
        $s = new Stats(array(1,4,7,5,'height'));
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanciateWithNoArrayShouldFail()
    {
        $s = new Stats(1, 2, 3);
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testMegingWithArrayHavingNonNumericValuesShouldFail()
    {
        $s = new Stats();
        $s->merge(array(1,4,7,5,'height'));
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
        $s = new Stats(array(1,2,3,4,5,6,7));
        $this->assertEquals((float) 4.472136, round($s->rootMeanSquare(), 6));
        $this->assertEquals((float) 4.472136, round($s->rms(), 6));
        $this->assertEquals((float) 4.472136, round($s->quadraticMean(), 6));
        $this->assertEquals((float) 4.472136, round($s->root_mean_square, 6));
        $this->assertEquals((float) 4.472136, round($s->rms, 6));
        $this->assertEquals((float) 4.472136, round($s->quadratic_mean, 6));
    }

    public function testComputingGeneralizedMeanShouldSuccess()
    {
        $this->markTestIncomplete();
    }


    public function testGettingHeronianMeanShouldSuccess()
    {
        $s = new Stats(array(2, 7));
        $this->assertEquals((float) 4.247, round($s->heronianMean(), 3));
        $this->assertEquals((float) 4.247, round($s->heronian(), 3));
        $this->assertEquals((float) 4.247, round($s->heronian_mean, 3));
        $this->assertEquals((float) 4.247, round($s->heronian, 3));
        $s = new Stats(array(5, 10));
        $this->assertEquals((float) 7.357, round($s->heronianMean(), 3));
        $this->assertEquals((float) 7.357, round($s->heronian(), 3));
        $this->assertEquals((float) 7.357, round($s->heronian_mean, 3));
        $this->assertEquals((float) 7.357, round($s->heronian, 3));
    }

    public function testGettingLehmerMeanShouldSucess()
    {
        $this->markTestIncomplete();
    }

    public function testGettingLehmerMeanWithNegativeNumbersShouldFail()
    {
        $this->markTestIncomplete();
    }
    public function testGettingContraharmonicMeanShouldSucess()
    {
        $this->markTestIncomplete();
    }


    public function testEqualityOfLehmerMeanWithOtherMeans()
    {
        $s = new Stats(array(2,5));
        $this->assertEquals($s->harmonic_mean, $s->lehmerMean(0));
        $this->assertEquals($s->geometric_mean, $s->lehmerMean(1/2));
        $this->assertEquals($s->mean, $s->lehmerMean(1));
        $this->assertEquals($s->contraharmonic_mean, $s->lehmerMean(2));
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingNegativeNumbersShouldFail()
    {
        $s = new Stats(array(-2, 7));
        $s->heronianMean();
    }
    
    
    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingMoreThanTwoElementsShouldFail()
    {
        $s = new Stats(array(2, 7, 3));
        $s->heronianMean();
    }

    
    /**
     * @expectedException \RuntimeException
     */
    public function testGettingHeronianMeanWithCollectionHavingLessThanTwoElementsShouldFail()
    {
        $s = new Stats(array(2));
        $s->heronianMean();
    }


    /**
     *@expectedException \InvalidArgumentException
     */
    public function testComputingGeneralizedMeanWithPEqualZeroShouldFail()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $s->generalizedMean(0);
    }

    /**
     *@expectedException \InvalidArgumentException
     */
    public function testComputingGeneralizedMeanWithPNegativeShouldFail()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $s->generalizedMean(-3);
    }

    /**
     *@expectedException \RuntimeException
     */
    public function testComputingGeneralizedMeanWithCollectionHavingNegativeNumbersShouldFail()
    {
        $s = new Stats(array(1,2,-3,4,3,7,2));
        $s->generalizedMean(3);
    }

    public function testGettingRangeShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,3,7,2));
        $this->assertEquals(6, $s->range());
        $this->assertEquals(6, $s->range);
    }

    public function testGettingVarianceShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 0.667, round($s->variance(), 3));
        $this->assertEquals((float) 0.667, round($s->variance, 3));
        $this->assertEquals((float) 0.667, round($s->var, 3));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingVarianceFromVoidCollectionShouldFail()
    {
        $s = new Stats();
        $s->variance;
    }

    public function testGettingStandardDeviationShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 0.816, round($s->standardDeviation(), 3));
        $this->assertEquals((float) 0.816, round($s->standard_deviation, 3));
        $this->assertEquals((float) 0.816, round($s->stddev, 3));
        $this->assertEquals((float) 0.816, round($s->stdev, 3));
    }
    
    public function testGettingSampleVarianceShouldSuccess()
    {
        $s = new Stats(array(1,2,3));
        $this->assertEquals((float) 1, $s->sampleVariance());
        $this->assertEquals((float) 1, $s->sample_variance);
        $this->assertEquals((float) 1, $s->s2);
    }
    

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingVarianceFromCollectionHavingLessThanTwoElementsShouldFail()
    {
        $s = new Stats(array(5));
        $s->sample_variance;
    }

    public function testGettingKurtosisShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5));
        $this->assertEquals((float) -1.3, $s->kurtosis());
        $this->assertEquals((float) -1.3, $s->kurtosis);
        
        $s = new Stats(array(1,2,3,4,500, 6));
        $this->assertEquals((float) 1.199, round($s->kurtosis(), 3));
        $this->assertEquals((float) 1.199, round($s->kurtosis, 3));
    }

    public function testGettingKurtosisTypeShouldSuccess()
    {
        $s = new Stats(array(1,2,3,4,5));
        $this->assertTrue($s->isPlatykurtic());
        $this->assertFalse($s->isLeptokurtic());
        $this->assertFalse($s->isMesokurtic());
        
        $s = new Stats(array(1,2,3,4,500, 6));
        $this->assertFalse($s->isPlatykurtic());
        $this->assertTrue($s->isLeptokurtic());
        $this->assertFalse($s->isMesokurtic());
    }


    public function testGettingQuartileShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals(15, $s->quartile(1));
        $this->assertEquals(15, $s->first_quartile);
        $this->assertEquals(26, $s->quartile(2));
        $this->assertEquals(26, $s->second_quartile);
        $this->assertEquals(26, $s->median);
        $this->assertEquals(37, $s->quartile(3));
        $this->assertEquals(37, $s->third_quartile);
        $this->assertEquals(37, $s->last_quartile);
    }

    public function testGettingInterquartileRangeShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals(22, $s->interquartileRange());
        $this->assertEquals(22, $s->iqr());
        $this->assertEquals(22, $s->iqr);
        $this->assertEquals(22, $s->IQR);
        $this->assertEquals(22, $s->interquartile_range);
    }

    public function testGettingSkewnessShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertEquals((float) 0.181, round($s->skewness(), 3));
        $this->assertEquals((float) 0.181, round($s->skew(), 3));
        $this->assertEquals((float) 0.181, round($s->skew, 3));
        $this->assertEquals((float) 0.181, round($s->skewness, 3));
    }

    public function testIfSkewIsNegativeShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertFalse($s->isLeftSkewed());
        $this->assertFalse($s->is_left_skewed);
        $this->assertFalse($s->left_skewed);
        $this->assertFalse($s->is_negative_skew);
        $this->assertFalse($s->negative_skew);
        $this->assertFalse($s->is_left_tailed);
        $this->assertFalse($s->left_tailed);
        $this->assertFalse($s->skewed_to_the_left);

        $s = new Stats(array(1,1001,1002,1003));
        $this->assertTrue($s->isLeftSkewed());
        $this->assertTrue($s->is_left_skewed);
        $this->assertTrue($s->left_skewed);
        $this->assertTrue($s->is_negative_skew);
        $this->assertTrue($s->negative_skew);
        $this->assertTrue($s->is_left_tailed);
        $this->assertTrue($s->left_tailed);
        $this->assertTrue($s->skewed_to_the_left);
    }
    
    public function testIfSkewIsPositiveShouldSuccess()
    {
        $s = new Stats(array(1, 11, 15, 19, 20, 24, 28, 34, 37, 47, 50, 57));
        $this->assertTrue($s->isRightSkewed());
        $this->assertTrue($s->is_right_skewed);
        $this->assertTrue($s->right_skewed);
        $this->assertTrue($s->is_positive_skew);
        $this->assertTrue($s->positive_skew);
        $this->assertTrue($s->is_right_tailed);
        $this->assertTrue($s->right_tailed);
        $this->assertTrue($s->skewed_to_the_right);

        $s = new Stats(array(1,2,3,1000));
        $this->assertTrue($s->isRightSkewed());
        $this->assertTrue($s->is_right_skewed);
        $this->assertTrue($s->right_skewed);
        $this->assertTrue($s->is_positive_skew);
        $this->assertTrue($s->positive_skew);
        $this->assertTrue($s->is_right_tailed);
        $this->assertTrue($s->right_tailed);
        $this->assertTrue($s->skewed_to_the_right);
    }

    public function testGettingFrequency()
    {
        $s = new Stats(array(1,3,1,5,3,3));
        $this->assertEquals(array('1' => 2, '3' => 3, '5' => 1), $s->frequency());
    }
}
