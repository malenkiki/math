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

use Malenki\Math\Complex;
use Malenki\Math\Angle;

class ComplexTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateOK()
    {
        $m = new Complex(2, 3);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testInstanciateKO()
    {
        $z = new Complex(2, 'A');
    }


    public function testRealAndImaginaryGetters()
    {
        $z = new Complex(2, 3);
        $this->assertEquals(2,$z->real);
        $this->assertEquals(2,$z->re);
        $this->assertEquals(2,$z->r);
        $this->assertEquals(3,$z->imaginary);
        $this->assertEquals(3,$z->im);
        $this->assertEquals(3,$z->i);
        
        $z = new Complex(1, 1);
        $this->assertEquals(sqrt(2),$z->rho);
        $this->assertEquals(pi() / 4,$z->theta);
    }

    public function testComplexFromAlgebraicFormToString()
    {
        $z = new Complex(2, 3);
        $this->assertEquals('2+3i', sprintf('%s', $z));
        
        $z = new Complex(2, -3);
        $this->assertEquals('2-3i', sprintf('%s', $z));
        
        $z = new Complex(-2, 3);
        $this->assertEquals('-2+3i', sprintf('%s', $z));
        
        $z = new Complex(-2, -3);
        $this->assertEquals('-2-3i', sprintf('%s', $z));
        
        $z = new Complex(2, 0);
        $this->assertEquals('2', sprintf('%s', $z));
        
        $z = new Complex(-2, 0);
        $this->assertEquals('-2', sprintf('%s', $z));
        
        $z = new Complex(0, 2);
        $this->assertEquals('2i', sprintf('%s', $z));
        
        $z = new Complex(0, -2);
        $this->assertEquals('-2i', sprintf('%s', $z));
        
        $z = new Complex(0, 1);
        $this->assertEquals('i', sprintf('%s', $z));
        
        $z = new Complex(0, -1);
        $this->assertEquals('-i', sprintf('%s', $z));
        
        $z = new Complex(2, 1);
        $this->assertEquals('2+i', sprintf('%s', $z));
        
        $z = new Complex(2, -1);
        $this->assertEquals('2-i', sprintf('%s', $z));
        
        $z = new Complex(-2, 1);
        $this->assertEquals('-2+i', sprintf('%s', $z));
        
        $z = new Complex(-2, -1);
        $this->assertEquals('-2-i', sprintf('%s', $z));
        
    }



    public function testComplexFromTrigonometricFormToString()
    {
        $z = new Complex(2, new Angle(M_PI / 2));
        $this->assertEquals(sprintf('%1$f(cos %2$f + i⋅sin %2$f)', 2, M_PI/2), sprintf('%s', $z));
        
        $z = new Complex(2, new Angle(90, Angle::TYPE_DEG));
        $this->assertEquals(sprintf('%1$f(cos %2$f + i⋅sin %2$f)', 2, M_PI/2), sprintf('%s', $z));
        
        $z = new Complex(1, new Angle(M_PI / 2));
        $this->assertEquals(sprintf('cos %1$f + i⋅sin %1$f', M_PI/2), sprintf('%s', $z));
    }
    
    
    public function testEqualTo()
    {
        $z = new Complex(2, 3);
        $zz = new Complex(2, 3);

        $this->assertTrue($z->equal($zz));
        $this->assertTrue($zz->equal($z));
    }
    
    public function testNotEqualTo()
    {
        $z = new Complex(2, 3);
        $zz = new Complex(3, 2);

        $this->assertFalse($z->equal($zz));
        $this->assertFalse($zz->equal($z));
    }



    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreatingComplexFromTrigonometricFormWithNegativeRhoThatRaiseException()
    {
        $z = new Complex(-1, pi() / 2, Complex::TRIGONOMETRIC);
    }

    public function testCreatingComplexFromTrigonometricFormUsingOnlyFloat()
    {
        $z = new Complex(1, pi() / 2, Complex::TRIGONOMETRIC);
        $zz = new Complex(0, 1);

        $this->assertTrue($zz->equal($z));
        
        $z = new Complex(1, pi(), Complex::TRIGONOMETRIC);
        $zz = new Complex(-1, 0);

        $this->assertTrue($zz->equal($z));
    }

    public function testCreatingComplexFromTrigonometricFormUsingAngleObject()
    {
        $a = new Angle(90, Angle::TYPE_DEG);
        $z = new Complex(1, $a, Complex::TRIGONOMETRIC);
        $zz = new Complex(0, 1);

        $this->assertTrue($zz->equal($z));
        
        $a = new Angle(180, Angle::TYPE_DEG);
        $z = new Complex(1, $a, Complex::TRIGONOMETRIC);
        $zz = new Complex(-1, 0);

        $this->assertTrue($zz->equal($z));
    }


    public function testCreatingComplexFromTrigonometricFormUsingAngleObjectAndWithoutThirdArgument()
    {
        $a = new Angle(M_PI / 2);
        $z = new Complex(1, $a);
        $zz = new Complex(0, 1);

        $this->assertTrue($zz->equal($z));
        
        $a = new Angle(M_PI);
        $z = new Complex(1, $a);
        $zz = new Complex(-1, 0);

        $this->assertTrue($zz->equal($z));
    }



    public function testArgument()
    {
        $z = new Complex(1, 1);
        $this->assertEquals(pi()/4, $z->argument());
        $this->assertEquals(pi()/4, $z->theta);
        $this->assertEquals(pi()/4, $z->arg);
        $this->assertEquals(pi()/4, $z->argument);
        
        $z = new Complex(1, 0);
        $this->assertEquals(0, $z->argument());
        $this->assertEquals(0, $z->theta);
        $this->assertEquals(0, $z->arg);
        $this->assertEquals(0, $z->argument);
        
        $z = new Complex(0, 1);
        $this->assertEquals(pi() / 2, $z->argument());
        $this->assertEquals(pi() / 2, $z->theta);
        $this->assertEquals(pi() / 2, $z->arg);
        $this->assertEquals(pi() / 2, $z->argument);
        
        $z = new Complex(-1, -1);
        $this->assertEquals(-3 * pi() / 4, $z->argument());
        $this->assertEquals(-3 * pi() / 4, $z->theta);
        $this->assertEquals(-3 * pi() / 4, $z->arg);
        $this->assertEquals(-3 * pi() / 4, $z->argument);
        
        $z = new Complex(-1, 0);
        $this->assertEquals(pi(), $z->argument());
        $this->assertEquals(pi(), $z->theta);
        $this->assertEquals(pi(), $z->arg);
        $this->assertEquals(pi(), $z->argument);
        
        $z = new Complex(0, -1);
        $this->assertEquals(pi() / -2, $z->argument());
        $this->assertEquals(pi() / -2, $z->theta);
        $this->assertEquals(pi() / -2, $z->arg);
        $this->assertEquals(pi() / -2, $z->argument);
    }
    
    public function testModulus()
    {
        $z = new Complex(1, 1);
        $this->assertEquals(sqrt(2), $z->modulus());
        $this->assertEquals(sqrt(2), $z->rho);
        $this->assertEquals(sqrt(2), $z->modulus);
        $this->assertEquals(sqrt(2), $z->norm);
        $z = new Complex(2, 4);
        $this->assertEquals(2 * sqrt(5), $z->modulus());
        $this->assertEquals(2 * sqrt(5), $z->rho);
        $this->assertEquals(2 * sqrt(5), $z->modulus);
        $this->assertEquals(2 * sqrt(5), $z->norm);
        $z = new Complex(1, 2);
        $this->assertEquals(sqrt(5), $z->modulus());
        $this->assertEquals(sqrt(5), $z->rho);
        $this->assertEquals(sqrt(5), $z->modulus);
        $this->assertEquals(sqrt(5), $z->norm);
    }


    public function testConjugate()
    {
        $z = new Complex(1, 1);
        $zc = new Complex(1, -1);
        $this->assertTrue($zc->equal($z->conjugate()));
        $this->assertTrue($zc->equal($z->conjugate));
        
        $z = new Complex(1, -1);
        $zc = new Complex(1, 1);
        $this->assertTrue($zc->equal($z->conjugate()));
        $this->assertTrue($zc->equal($z->conjugate));
    }

    public function testAdditionUsingComplexAndRealNumber()
    {
        $r = -5;
        $z = new Complex(1, 1);
        $za = new Complex(3, -2);
        $zs = new Complex(4, -1);
        $this->assertTrue($zs->equal($z->add($za)));

        $zs = new Complex(-4, 1);
        $this->assertTrue($zs->equal($z->add($r)));
    }


    public function testMultiplicationOfComplexWithComplex()
    {
        $z = new Complex(1, 2);
        $za = new Complex(3, 4);
        $r = new Complex(-5, 10);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Complex(3, -4);
        $r = new Complex(11, 2);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Complex(-3, -4);
        $r = new Complex(5, -10);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Complex(-3, 4);
        $r = new Complex(-11, -2);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
    }
    
    
    public function testMultiplicationOfComplexWithRealNumber()
    {
        $z = new Complex(1, 2);
        $real = 4;
        $r = new Complex(4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Complex(-4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $z = new Complex(1, -2);
        $real = 4;
        $r = new Complex(4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Complex(-4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        
        $z = new Complex(-1, 2);
        $real = 4;
        $r = new Complex(-4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Complex(4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $z = new Complex(-1, -2);
        $real = 4;
        $r = new Complex(-4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Complex(4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
    }    
}
