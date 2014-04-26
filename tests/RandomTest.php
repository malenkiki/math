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

use Malenki\Math\Random;

class RandomTest extends PHPUnit_Framework_TestCase
{
    public function testGettingManyIntegerItemsSuccess()
    {
        $r = new Random(0, 9);
        $this->assertEquals(2, count($r->getMany(2)));
        $this->assertEquals(3, count($r->getMany(3)));
        $this->assertEquals(4, count($r->getMany(4)));
        $this->assertEquals(5, count($r->getMany(5)));
        $this->assertEquals(6, count($r->getMany(6)));
        $this->assertEquals(7, count($r->getMany(7)));
        $this->assertEquals(8, count($r->getMany(8)));
        $this->assertEquals(9, count($r->getMany(9)));
        $this->assertEquals(10, count($r->getMany(10)));
        $this->assertEquals(11, count($r->getMany(11)));
    }

    public function testGettingManyDoubleItemsSuccess()
    {
        $r = new Random();
        $this->assertEquals(2, count($r->getMany(2)));
        $this->assertEquals(3, count($r->getMany(3)));
        $this->assertEquals(4, count($r->getMany(4)));
        $this->assertEquals(5, count($r->getMany(5)));
        $this->assertEquals(6, count($r->getMany(6)));
        $this->assertEquals(7, count($r->getMany(7)));
        $this->assertEquals(8, count($r->getMany(8)));
        $this->assertEquals(9, count($r->getMany(9)));
        $this->assertEquals(10, count($r->getMany(10)));
        $this->assertEquals(11, count($r->getMany(11)));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGettingManyItemsWhithArgLessThan2RaisesException()
    {
        $r = new Random(0, 9);
        $r->getMany(1);
        $r = new Random();
        $r->getMany(1);
    }

    public function testGettingManyIntegerItemsWithoutReplacementSuccess()
    {
        $r = new Random(0, 9);
        $this->assertEquals(2, count(array_unique($r->getManyWithoutReplacement(2), SORT_NUMERIC)));
        $this->assertEquals(3, count(array_unique($r->getManyWithoutReplacement(3), SORT_NUMERIC)));
        $this->assertEquals(4, count(array_unique($r->getManyWithoutReplacement(4), SORT_NUMERIC)));
        $this->assertEquals(5, count(array_unique($r->getManyWithoutReplacement(5), SORT_NUMERIC)));
        $this->assertEquals(6, count(array_unique($r->getManyWithoutReplacement(6), SORT_NUMERIC)));
        $this->assertEquals(7, count(array_unique($r->getManyWithoutReplacement(7), SORT_NUMERIC)));
        $this->assertEquals(8, count(array_unique($r->getManyWithoutReplacement(8), SORT_NUMERIC)));
        $this->assertEquals(9, count(array_unique($r->getManyWithoutReplacement(9), SORT_NUMERIC)));
        $this->assertEquals(10, count(array_unique($r->getManyWithoutReplacement(10), SORT_NUMERIC)));
    }

    public function testGettingManyDoubleItemsWithoutReplacementSuccess()
    {
        $r = new Random();
        $this->assertEquals(2, count(array_unique($r->getManyWithoutReplacement(2), SORT_NUMERIC)));
        $this->assertEquals(3, count(array_unique($r->getManyWithoutReplacement(3), SORT_NUMERIC)));
        $this->assertEquals(4, count(array_unique($r->getManyWithoutReplacement(4), SORT_NUMERIC)));
        $this->assertEquals(5, count(array_unique($r->getManyWithoutReplacement(5), SORT_NUMERIC)));
        $this->assertEquals(6, count(array_unique($r->getManyWithoutReplacement(6), SORT_NUMERIC)));
        $this->assertEquals(7, count(array_unique($r->getManyWithoutReplacement(7), SORT_NUMERIC)));
        $this->assertEquals(8, count(array_unique($r->getManyWithoutReplacement(8), SORT_NUMERIC)));
        $this->assertEquals(9, count(array_unique($r->getManyWithoutReplacement(9), SORT_NUMERIC)));
        $this->assertEquals(10, count(array_unique($r->getManyWithoutReplacement(10), SORT_NUMERIC)));
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testGettingManyIntegerItemsWithoutReplacementRaisesOutOfRange()
    {
        $r = new Random(0, 9);
        $r->getManyWithoutReplacement(11);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGettingManyItemsWithoutReplacementWhithArgLessThan2RaisesException()
    {
        $r = new Random(0, 9);
        $r->getManyWithoutReplacement(1);
        $r = new Random();
        $r->getManyWithoutReplacement(1);
    }
}
