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

use Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank;
use Malenki\Math\Stats\Stats;

class WilcoxonSignedRankTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWSRTShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank',
            $w
        );
    }

    public function testAddingTwoEqualSamplesShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(new Stats(array(1, 2, 3)));
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank',
            $w->add(new Stats(array(6, 3, 4)))
        );
        
        $w = new WilcoxonSignedRank();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank',
            $w->add(array(6, 3, 4))->add(array(7, 5, 9))
        );
        
        
        $w = new WilcoxonSignedRank();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank',
            $w->set(array(6, 3, 4), array(7, 5, 9))
        );
        
        $w = new WilcoxonSignedRank();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonSignedRank',
            $w->set(new Stats(array(6, 3, 4)), new Stats(array(7, 5, 9)))
        );
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddingTwoNonEqualSamplesShouldFail()
    {
        $w = new WilcoxonSignedRank();
        $w->add(new Stats(array(1, 2, 3)));
        $w->add(new Stats(array(1, 2, 3, 4)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddingMoreThanTwoSamplesShouldFail()
    {
        $w = new WilcoxonSignedRank();
        $w->add(new Stats(array(1, 2, 3)));
        $w->add(new Stats(array(1, 2, 3)));
        $w->add(new Stats(array(1, 2, 3)));
    }

    public function testGettingASignsShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $should = array(0, 1, 1, -1, -1, -1, -1, 1, 1, 1);
        $this->assertEquals($should, $w->signs());
    }
}
