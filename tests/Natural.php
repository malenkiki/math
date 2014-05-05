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

use Malenki\Math\Number\Natural;

class NaturalTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateShouldSuccess()
    {
        $n = new Natural(2);
        $this->assertInstanceOf('\Malenki\Math\Number\Natural', $n);
    }

    public function testInstanciateWithNonIntegerShouldFail()
    {
        $n = new Natural(2.6);
    }

    public function testIfNaturalNumberIsZeroOrNotShouldSuccess()
    {
        $n = new Natural(0);
        $this->assertTrue($n->isZero());
        $n = new Natural(4);
        $this->assertFalse($n->isZero());
    }

    public function testIfNaturalNumberIsEvenOrNotShouldSuccess()
    {
        $n = new Natural(2);
        $this->assertTrue($n->isEven());
        $n = new Natural(3);
        $this->assertFalse($n->isEven());
    }

    public function testIfIntegerIsOddOrNotShouldSuccess()
    {
        $n = new Natural(5);
        $this->assertTrue($n->isOdd());
        $n = new Natural(8);
        $this->assertFalse($n->isOdd());
    }

    public function testStringContextShouldSuccess()
    {
        $n = new Natural(5);
        $this->assertEquals('5', "$n");
    }
}
