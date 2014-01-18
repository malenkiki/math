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



class ComplexTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateOK()
    {
        $m = new Malenki\Math\Complex(2, 3);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testInstanciateKO()
    {
        $z = new Malenki\Math\Complex(2, 'A');
    }


    public function testRealAndImaginaryGetters()
    {
        $z = new Malenki\Math\Complex(2, 3);
        $this->assertEquals(2,$z->real);
        $this->assertEquals(2,$z->re);
        $this->assertEquals(2,$z->r);
        $this->assertEquals(3,$z->imaginary);
        $this->assertEquals(3,$z->im);
        $this->assertEquals(3,$z->i);
    }

    public function testToString()
    {
        $z = new Malenki\Math\Complex(2, 3);
        $this->assertEquals('2+3i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(2, -3);
        $this->assertEquals('2-3i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(-2, 3);
        $this->assertEquals('-2+3i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(-2, -3);
        $this->assertEquals('-2-3i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(2, 0);
        $this->assertEquals('2', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(-2, 0);
        $this->assertEquals('-2', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(0, 2);
        $this->assertEquals('2i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(0, -2);
        $this->assertEquals('-2i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(0, 1);
        $this->assertEquals('i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(0, -1);
        $this->assertEquals('-i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(2, 1);
        $this->assertEquals('2+i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(2, -1);
        $this->assertEquals('2-i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(-2, 1);
        $this->assertEquals('-2+i', sprintf('%s', $z));
        
        $z = new Malenki\Math\Complex(-2, -1);
        $this->assertEquals('-2-i', sprintf('%s', $z));
        
    }
    
    
    public function testEqualTo()
    {
        $z = new Malenki\Math\Complex(2, 3);
        $zz = new Malenki\Math\Complex(2, 3);

        $this->assertTrue($z->equal($zz));
        $this->assertTrue($zz->equal($z));
    }
    
    public function testNotEqualTo()
    {
        $z = new Malenki\Math\Complex(2, 3);
        $zz = new Malenki\Math\Complex(3, 2);

        $this->assertFalse($z->equal($zz));
        $this->assertFalse($zz->equal($z));
    }

    public function testCreatingComplexFromPolarCoordinates()
    {
        $z = \Malenki\Math\Complex::fromPolar(1, pi() / 2);
        $zz = new Malenki\Math\Complex(0, 1);

        $this->assertTrue($zz->equal($z));
        
        $z = \Malenki\Math\Complex::fromPolar(1, pi());
        $zz = new Malenki\Math\Complex(-1, 0);

        $this->assertTrue($zz->equal($z));
    }


    public function testArgument()
    {
        $z = new Malenki\Math\Complex(1, 1);
        $this->assertEquals(pi()/4, $z->argument());
        
        $z = new Malenki\Math\Complex(1, 0);
        $this->assertEquals(0, $z->argument());
        
        $z = new Malenki\Math\Complex(0, 1);
        $this->assertEquals(pi() / 2, $z->argument());
        
        $z = new Malenki\Math\Complex(-1, -1);
        $this->assertEquals(-3 * pi() / 4, $z->argument());
        
        $z = new Malenki\Math\Complex(-1, 0);
        $this->assertEquals(pi(), $z->argument());
        
        $z = new Malenki\Math\Complex(0, -1);
        $this->assertEquals(pi() / -2, $z->argument());
    }

    public function testConjugate()
    {
        $z = new Malenki\Math\Complex(1, 1);
        $zc = new Malenki\Math\Complex(1, -1);
        $this->assertTrue($zc->equal($z->conjugate()));
        
        $z = new Malenki\Math\Complex(1, -1);
        $zc = new Malenki\Math\Complex(1, 1);
        $this->assertTrue($zc->equal($z->conjugate()));
    }

    public function testAdditionUsingComplexAndRealNumber()
    {
        $r = -5;
        $z = new Malenki\Math\Complex(1, 1);
        $za = new Malenki\Math\Complex(3, -2);
        $zs = new Malenki\Math\Complex(4, -1);
        $this->assertTrue($zs->equal($z->add($za)));

        $zs = new Malenki\Math\Complex(-4, 1);
        $this->assertTrue($zs->equal($z->add($r)));
    }


    public function testMultiplicationOfComplexWithComplex()
    {
        $z = new Malenki\Math\Complex(1, 2);
        $za = new Malenki\Math\Complex(3, 4);
        $r = new Malenki\Math\Complex(-5, 10);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Malenki\Math\Complex(3, -4);
        $r = new Malenki\Math\Complex(11, 2);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Malenki\Math\Complex(-3, -4);
        $r = new Malenki\Math\Complex(5, -10);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
        
        $za = new Malenki\Math\Complex(-3, 4);
        $r = new Malenki\Math\Complex(-11, -2);
        $this->assertEquals($r, $z->multiply($za));
        $this->assertEquals($r, $za->multiply($z));
        $this->assertTrue($r->equal($z->multiply($za)));
        $this->assertTrue($r->equal($za->multiply($z)));
    }
    
    
    public function testMultiplicationOfComplexWithRealNumber()
    {
        $z = new Malenki\Math\Complex(1, 2);
        $real = 4;
        $r = new Malenki\Math\Complex(4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Malenki\Math\Complex(-4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $z = new Malenki\Math\Complex(1, -2);
        $real = 4;
        $r = new Malenki\Math\Complex(4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Malenki\Math\Complex(-4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        
        $z = new Malenki\Math\Complex(-1, 2);
        $real = 4;
        $r = new Malenki\Math\Complex(-4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Malenki\Math\Complex(4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $z = new Malenki\Math\Complex(-1, -2);
        $real = 4;
        $r = new Malenki\Math\Complex(-4, -8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
        
        $real = -4;
        $r = new Malenki\Math\Complex(4, 8);
        $this->assertEquals($r, $z->multiply($real));
        $this->assertTrue($r->equal($z->multiply($real)));
    }    
}
