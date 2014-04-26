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

use Malenki\Math\RandomComplex;

class RandomComplexTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingMultipleComplexOnlyWithPositiveReals()
    {
        $r = new RandomComplex();
        $r->r(2.2, 7.4);
        $arr = $r->getMany(100);

        foreach ($arr as $z) {
            $this->assertGreaterThanOrEqual(2.2, $z->r);
            $this->assertLessThanOrEqual(7.4, $z->r);
        }
    }

    public function testCreatingMultipleComplexOnlyWithNegativeReals()
    {
        $r = new RandomComplex();
        $r->r(-4.3, -1.2);
        $arr = $r->getMany(100);

        foreach ($arr as $k => $z) {
            $this->assertGreaterThanOrEqual(-4.3, $z->r);
            $this->assertLessThanOrEqual(-1.2, $z->r);
        }
    }

    public function testCreatingMultipleComplexOnlyWithNegativeAndPositiveReals()
    {
        $r = new RandomComplex();
        $r->r(-3.2, 5.6);
        $arr = $r->getMany(100);

        foreach ($arr as $z) {
            $this->assertGreaterThanOrEqual(-3.2, $z->r);
            $this->assertLessThanOrEqual(5.6, $z->r);
        }
    }

    public function testCreatingMultipleComplexWithPositiveRealsHavingSameWholePart()
    {
        $r = new RandomComplex();
        $r->r(3.2, 3.6);
        $arr = $r->getMany(100);

        foreach ($arr as $z) {
            $this->assertGreaterThanOrEqual(3.2, $z->r);
            $this->assertLessThanOrEqual(3.6, $z->r);
        }
    }

    public function testCreatingMultipleComplexWithNegativeRealsHavingSameWholePart()
    {
        $r = new RandomComplex();
        $r->r(-3.8, -3.2);
        $arr = $r->getMany(100);

        foreach ($arr as $z) {
            $this->assertGreaterThanOrEqual(-3.8, $z->r);
            $this->assertLessThanOrEqual(-3.2, $z->r);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAssigningWrongTypeForRealsOfValuesThatRaiseInvalidArgumentException()
    {
        $r = new RandomComplex();
        $r->r(4.1, 2.3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAssigningWrongTypeForImaginariesOfValuesThatRaiseInvalidArgumentException()
    {
        $r = new RandomComplex();
        $r->i(4.1, 2.3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAssigningWrongTypeForRhosOfValuesThatRaiseInvalidArgumentException()
    {
        $r = new RandomComplex();
        $r->rho(4.1, 2.3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAssigningNegativeRhosThatRaiseInvalidArgumentException()
    {
        $r = new RandomComplex();
        $r->rho(-3, 2.3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAssigningWrongTypeForThetasOfValuesThatRaiseInvalidArgumentException()
    {
        $r = new RandomComplex();
        $r->theta(4.1, 2.3);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingRhoWhileRealHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->r(1.2, 3.4)->rho(5.6, 7.8);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingThetaWhileRealHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->r(1.2, 3.4)->theta(M_PI / 2, M_PI);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingRhoWhileImaginaryHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->i(1.2, 3.4)->rho(5.6, 7.8);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingThetaWhileImaginaryHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->i(1.2, 3.4)->theta(M_PI / 2, M_PI);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingRealWhileRhoHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->rho(1.2, 3.4)->r(5.6, 7.8);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingRealWhileThetaHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->theta(1.2, 3.4)->r(5.6, 7.8);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingImaginaryWhileRhoHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->rho(1.2, 3.4)->i(5.6, 7.8);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testChainingImaginaryWhileThetaHasBeenUsedRaiseRuntimeException()
    {
        $r = new RandomComplex();
        $r->theta(1.2, 3.4)->i(5.6, 7.8);
    }

    public function testStartingWithAlgebraicThenResetThenUseTrigonometricWithoutException()
    {
        $r = new RandomComplex();
        $r->r(1.2, 3.4)->i(5.6, 7.8);
        $r->get();
        $r->reset()->rho(2, 5)->theta(M_PI/4, M_PI / 2);
    }

    public function testStartingWithTrigonometricThenResetThenUseAlgebraicWithoutException()
    {
        $r = new RandomComplex();
        $r->rho(2, 5)->theta(M_PI/4, M_PI / 2);
        $r->get();
        $r->reset()->r(1.2, 3.4)->i(5.6, 7.8);
    }
}
