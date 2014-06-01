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

use Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney;
use Malenki\Math\Stats\Stats;

class WilcoxonMannWhitneyTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWMWShouldSuccess()
    {
        $w = new WilcoxonMannWhitney();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney',
            $w
        );
    }
    
    public function testAddingTwoSamplesShouldSuccess()
    {
        $w = new WilcoxonMannWhitney();
        $w->add(new Stats(array(1, 2, 3)));
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney',
            $w->add(new Stats(array(6, 3, 4)))
        );
        
        $w = new WilcoxonMannWhitney();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney',
            $w->add(array(6, 3, 4))->add(array(7, 5, 9))
        );
        
        
        $w = new WilcoxonMannWhitney();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney',
            $w->set(array(6, 3, 4), array(7, 5, 9))
        );
        
        $w = new WilcoxonMannWhitney();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\NonParametricTest\WilcoxonMannWhitney',
            $w->set(new Stats(array(6, 3, 4)), new Stats(array(7, 5, 9)))
        );
    }



    /**
     * @expectedException \RuntimeException
     */
    public function testAddingMoreThanTwoSamplesShouldFail()
    {
        $w = new WilcoxonMannWhitney();
        $w->add(new Stats(array(1, 2, 3)));
        $w->add(new Stats(array(1, 2, 3)));
        $w->add(new Stats(array(1, 2, 3)));
    }

    public function testGettingU1AndU2ValuesShouldSuccess()
    {
        $w = new WilcoxonMannWhitney();
        $w->add(array(1, 4.5, 4.5, 6, 7, 8, 9.5, 11.5, 13.5, 15, 16.5, 18));
        $w->add(array(2, 3, 9.5, 11.5, 13.5, 16.5, 19, 20, 21, 22));

        $this->assertEquals(37, $w->u2());
        $this->assertEquals(37, $w->u2);
        $this->assertEquals(83, $w->u1());
        $this->assertEquals(83, $w->u1);

        $this->assertEquals(37, $w->u());
        $this->assertEquals(37, $w->u);

    }
}
