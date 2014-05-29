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

    public function testGettingSignsShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        $should = array(0, 1, 1, -1, -1, -1, -1, 1, 1, 1);
        $this->assertEquals($should, $w->signs());
    }


    public function testGettingAbsoluteValuesShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        $should = array(0, 5, 5, 7, 9, 10, 12, 15, 17, 20);
        $this->assertEquals($should, $w->absoluteValues());
    }
    
    public function testGettingRanksShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        // idx having 0 as abs value are not included, in this case, one value 
        // equals 0 so this is at the start, with index 0, so resulting array 
        // must start its keys at 1
        $should = array(
            1 => 1.5,
            2 => 1.5,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9
        );
        $this->assertEquals($should, $w->ranks());
        
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 116));
        $w->add(array(125, 122, 115, 123));
        $should = array(1, 3, 3, 3);
        $this->assertEquals($should, $w->ranks());
        
        $this->markTestIncomplete();
        // Still bogus, but i want dev other part before correct this
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 100));
        $w->add(array(125, 122, 115, 123));
        $should = array(1, 2.5, 2.5, 4);
        $this->assertEquals($should, $w->ranks());
    }
    
    
    public function testGettingSignedRanksShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        // idx having 0 as abs value are not included, in this case, one value 
        // equals 0 so this is at the start, with index 0, so resulting array 
        // must start its keys at 1
        $should = array(
            1 => 1.5,
            2 => 1.5,
            3 => -3,
            4 => -4,
            5 => -5,
            6 => -6,
            7 => 7,
            8 => 8,
            9 => 9
        );
        $this->assertEquals($should, $w->signedRanks());
        
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 116));
        $w->add(array(125, 122, 115, 123));
        $should = array(-1, 3, 3, -3);
        $this->assertEquals($should, $w->signedRanks());
        
        $this->markTestIncomplete();
        // Still bogus, but i want dev other part before correct this
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 100));
        $w->add(array(125, 122, 115, 123));
        $should = array(-1, 2.5, -2.5, 4);
        $this->assertEquals($should, $w->signedRanks());
    }
    
    
    public function testGettingNrShouldSuccess()
    {
        $w = new WilcoxonSignedRank();
        $w->add(array(110, 122, 125, 120, 140, 124, 123, 137, 135, 145));
        $w->add(array(125, 115, 130, 140, 140, 115, 140, 125, 140, 135));
        $this->assertEquals(9, $w->nr());
        $this->assertCount(9, $w);
        
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 116));
        $w->add(array(125, 122, 115, 123));
        $this->assertEquals(4, $w->nr());
        $this->assertCount(4, $w);
        
        $w = new WilcoxonSignedRank();
        $w->add(array(126, 115, 122, 100));
        $w->add(array(125, 122, 115, 123));
        $this->assertEquals(4, $w->nr());
        $this->assertCount(4, $w);
    }
}