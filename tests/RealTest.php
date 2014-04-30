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

use Malenki\Math\Number\Real;

class RealTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateOK()
    {
        $i = new Real(2.56);
        $this->assertInstanceOf('\Malenki\Math\Number\Real', $i);
    }

    public function testIfRealIsZeroOrNotShouldSuccess()
    {
        $i = new Real(0);
        $this->assertTrue($i->isZero());
        $i = new Real(4);
        $this->assertFalse($i->isZero());
    }

    public function testIfRealAsDecimalPartOrNotShouldSuccess()
    {
        $i = new Real(2.56);
        $this->assertTrue($i->hasDecimal());
        $i = new Real(2);
        $this->assertFalse($i->hasDecimal());
    }

    public function testGettingDecimalPartShouldSuccess()
    {
        $i = new Real(2.56);
        $d = new Real(0.56);
        $this->assertEquals($d, $i->decimal());
    }
    
    public function testIfRealIsIntegerOrNotShouldSuccess()
    {
        $i = new Real(2.56);
        $this->assertFalse($i->isInteger());
        $i = new Real(2);
        $this->assertTrue($i->isInteger());
    }

}
