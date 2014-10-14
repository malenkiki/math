<?php
/*
Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>

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

use Malenki\Math\Matrix;
use Malenki\Math\Number\Complex;

class MatrixTest extends PHPUnit_Framework_TestCase
{
    public function testPopulateOK()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));

        $this->assertEquals(array(1, 2, 3), $m->getRow(0));
        $this->assertEquals(array(4, 5, 6), $m->getRow(1));

        $m = new Matrix(2, 3);
        $m->addRow(array(1, 2, 3));
        $m->addRow(array(4, 5, 6));

        $this->assertEquals(array(1, 2, 3), $m->getRow(0));
        $this->assertEquals(array(4, 5, 6), $m->getRow(1));

        $m = new Matrix(2, 3);
        $m->addCol(array(1, 4));
        $m->addCol(array(2, 5));
        $m->addCol(array(3, 6));

        $this->assertEquals(array(1, 4), $m->getCol(0));
        $this->assertEquals(array(2, 5), $m->getCol(1));
        $this->assertEquals(array(3, 6), $m->getCol(2));
    }

    public function testGettingValuesAsArrayShouldSuccess()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(1,2,3,4,5,6));
        $this->assertEquals(array(array(1,2,3), array(4,5,6)), $m->array);
        $this->assertEquals(array(array(1,2,3), array(4,5,6)), $m->getAll());
    }

    public function testGettingSubMatrix()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $r = new Matrix(1, 1);
        $r->populate(array(4));

        $this->assertEquals($r, $m->subMatrix(0, 0));

        $r = new Matrix(1, 1);
        $r->populate(array(3));

        $this->assertEquals($r, $m->subMatrix(0, 1));

        $r = new Matrix(1, 1);
        $r->populate(array(2));

        $this->assertEquals($r, $m->subMatrix(1, 0));

        $r = new Matrix(1, 1);
        $r->populate(array(1));

        $this->assertEquals($r, $m->subMatrix(1, 1));

        $m = new Matrix(3, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6, 7, 8, 9));

        $r = new Matrix(2, 2);
        $r->populate(array(1, 3, 7, 9));

        $this->assertEquals($r, $m->subMatrix(1, 1));

        $r = new Matrix(2, 2);
        $r->populate(array(5, 6, 8, 9));

        $this->assertEquals($r, $m->subMatrix(0, 0));

        $r = new Matrix(2, 2);
        $r->populate(array(1, 2, 4, 5));

        $this->assertEquals($r, $m->subMatrix(2, 2));
    }

    public function testIfSquareMatrixIsDiagonalOrNotShouldSuccess()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(2, 0, 0, 8));
        $this->assertTrue($m->isDiagonal());
        $this->assertTrue($m->is_diagonal);
        $this->assertTrue($m->diagonal);
        
        $m = new Matrix(2, 2);
        $m->populate(array(2, 4, 6, 8));
        $this->assertFalse($m->isDiagonal());
        $this->assertFalse($m->is_diagonal);
        $this->assertFalse($m->diagonal);
    }

    public function testIfNonSquareMatrixIsDiagonalOrNotShouldSuccess()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(2, 0, 0, 0, 8, 0));
        $this->assertTrue($m->isDiagonal());
        $this->assertTrue($m->is_diagonal);
        $this->assertTrue($m->diagonal);

        $m = new Matrix(3, 2);
        $m->populate(array(2, 0, 0, 1, 0, 0));
        $this->assertTrue($m->isDiagonal());
        $this->assertTrue($m->is_diagonal);
        $this->assertTrue($m->diagonal);
        
        $m = new Matrix(2, 3);
        $m->populate(array(2, 0, 2, 0, 8, 0));
        $this->assertFalse($m->isDiagonal());
        $this->assertFalse($m->is_diagonal);
        $this->assertFalse($m->diagonal);

        $m = new Matrix(3, 2);
        $m->populate(array(2, 0, 1, 0, 0, 4));
        $this->assertFalse($m->isDiagonal());
        $this->assertFalse($m->is_diagonal);
        $this->assertFalse($m->diagonal);
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddingTwoMatrixHavingDifferentSizeShouldFail()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(2, 0, 0, 0, 8, 0));
        $n = new Matrix(2, 2);
        $n->populate(array(2, 0, 0, 0));

        $m->add($n);
    }
        
    public function testMultiplyMatrixWithScalarOrComplexNumber()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $mr = new Matrix(2, 2);
        $mr->populate(array(2, 4, 6, 8));

        $this->assertEquals($mr, $m->multiply(2));

        $z = new Complex(1, 2);

        $mr = new Matrix(2, 2);
        $mr->populate(
            array($z->multiply(1), $z->multiply(2), $z->multiply(3), $z->multiply(4))
        );

        $this->assertEquals($mr, $m->multiply($z));

    }

    public function testMultiplyRealMatrixWithRealMatrix()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $n = new Matrix(2, 2);
        $n->populate(array(5, 6, 7, 8));

        $r = new Matrix(2, 2);
        $r->populate(array(19, 22, 43, 50));

        $this->assertEquals($r, $m->multiply($n));

        $r = new Matrix(2, 2);
        $r->populate(array(23, 34, 31, 46));

        $this->assertEquals($r, $n->multiply($m));

        $n = new Matrix(2, 3);
        $n->populate(array(5, 6, 7, 8, 9, 10));

        $r = new Matrix(2, 3);
        $r->populate(array(21,24,27,47,54,61));

        $this->assertEquals($r, $m->multiply($n));

        $n = new Matrix(2, 1);
        $n->populate(array(5, 6));

        $r = new Matrix(2, 1);
        $r->populate(array(17, 39));

        $this->assertEquals($r, $m->multiply($n));
    }

    public function testMultiplyRealMatrixWithComplexMatrix()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 3, 2, 4));

        $z = new Complex(1, 2);

        $mr = new Matrix(2, 2);
        $mr->populate(
            array($z->multiply(1), $z->multiply(3), $z->multiply(2), $z->multiply(4))
        );

        $mf = new Matrix(2, 2);
        $mf->populate(
            array(
                new Complex(7,14),
                new Complex(15,30),
                new Complex(10,20),
                new Complex(22,44)
            )
        );

        $this->assertEquals($mf, $m->multiply($mr));
    }

    public function testIfMatrixIsSquareShouldSuccess()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));
        $this->assertTrue($m->isSquare());
        $this->assertTrue($m->is_square);
        $m = new Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));
        $this->assertFalse($m->isSquare());
        $this->assertFalse($m->is_square);
    }

    public function testComputeDeterminantOfSquareMatrix()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $this->assertEquals(-2, $m->det());
        $this->assertEquals(-2, $m->det);

        $m = new Matrix(3, 3);
        $m->populate(array(0,2,3,4,5,6,0,8,9));

        $this->assertEquals(24, $m->det());
        $this->assertEquals(24, $m->det);

        $m = new Matrix(4, 4);
        $m->populate(array(-1,0,2,3,4,5,-6,0,8,-4,9,-3,0,3,-1,0));

        $this->assertEquals(621, $m->det());
        $this->assertEquals(621, $m->det);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testComputingDeterminantForNonSquareMatrixMustRaiseRuntimeException()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));

        $m->det();
    }

    public function testGettingInverseMatrixFromInversibleMatrixWillSuccess()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(2,3,4,5));

        $i = $m->inverse();
        $this->assertEquals(array(-5/2, 3/2), $i->getRow(0));
        $this->assertEquals(array(2, -1), $i->getRow(1));

        $m = new Matrix(3, 3);
        $m->populate(array(-1,2,5,1,2,3,-2,8,10));

        $i = $m->inverse;
        $this->assertEquals(array(-1/8, 5/8, -1/8), $i->getRow(0));
        $this->assertEquals(array(-0.5,0,0.25), $i->getRow(1));
        $this->assertEquals(array(3/8,1/8, -1/8), $i->getRow(2));

        $m = new Matrix(3, 3);
        $m->populate(array(-3, 5, 6, -1, 2, 2, 1, -1, -1));

        $this->assertEquals(array(0, 1, 2), $m->inverse->getRow(0));
        $this->assertEquals(array(-1, 3, 0), $m->inverse->getRow(1));
        $this->assertEquals(array(1, -2, 1), $m->inverse->getRow(2));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGettingInverseMatrixFromNonInversibleMatrixWillRaiseRuntimeException()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(2,0,4,0));

        $i = $m->inverse();
    }

    public function testAddRealMatrixWithPartlyComplexMatrix()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $z = new Matrix(2, 2);
        $z->populate(array(1, new Complex(2, 1), 3, 4));

        $r = new Matrix(2, 2);
        $r->populate(array(2, new Complex(4, 1), 6, 8));

        $this->assertEquals($r, $m->add($z));
        $this->assertEquals($r, $z->add($m));
    }

    public function testToString()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));

        $this->assertEquals("1  2  3\n4  5  6", sprintf('%s', $m));

        $m = new Matrix(2, 3);
        $m->populate(array(1, 20, 3, 4, 5, 60));

        $this->assertEquals("1  20   3\n4   5  60", sprintf('%s', $m));

        $m = new Matrix(2, 3);
        $m->populate(array(1, new Complex(2, 1), 3, 4, 5, 60));

        $this->assertEquals("1  2+i   3\n4    5  60", sprintf('%s', $m));
    }

    public function testGettingTraceShouldSuccess()
    {
        $m = new Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $this->assertEquals(5, $m->trace());
        $this->assertEquals(5, $m->trace);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGettingTraceFromNonSquareMatrixShouldFail()
    {
        $m = new Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));

        $m->trace();
    }


    public function testGettigExponentialMatrixShouldSuccess()
    {
        $this->markTestIncomplete();
    }
}
