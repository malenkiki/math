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

use Malenki\Math\Stats\ParametricTest\Anova;
use Malenki\Math\Stats\Stats;

class AnovaTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateAnovaShouldSuccess()
    {
        $a = new Anova();
        $this->assertInstanceOf('\Malenki\Math\Stats\ParametricTest\Anova', $a);
    }

    public function testAddingSamplesShouldSuccess()
    {
        $a = new Anova();
        $a->add(new Stats(array(1, 2, 3)));
        $a->add(new Stats(array(6, 3, 4)));
        $a->add(new Stats(array(7, 5, 9)));
        $this->assertCount(3, $a);
        $a->add(array(1, 2, 3));
        $a->add(array(6, 3, 4));
        $a->add(array(7, 5, 9));
        $this->assertCount(6, $a);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingBadSamplesShouldFail()
    {
        $a = new Anova();
        $a->add(1);
    }

    public function testGettingDofShouldSuccess()
    {
        $a = new Anova();
        $a->add(array(6, 8, 4, 5, 3, 4));
        $a->add(array(8, 12, 9, 11, 6, 8));
        $a->add(array(13, 9, 11, 8, 7, 12));

        $this->assertEquals(2, $a->degreesOfFreedom());
        $this->assertEquals(2, $a->degrees_of_freedom);
        $this->assertEquals(2, $a->dof());
        $this->assertEquals(2, $a->dof);
    }

    public function testGettingWithinGroupDofShouldSuccess()
    {
        $a = new Anova();
        $a->add(array(6, 8, 4, 5, 3, 4));
        $a->add(array(8, 12, 9, 11, 6, 8));
        $a->add(array(13, 9, 11, 8, 7, 12));

        $this->assertEquals(15, $a->withinGroupDegreesOfFreedom());
        $this->assertEquals(15, $a->within_group_degrees_of_freedom);
        $this->assertEquals(15, $a->wgdof());
        $this->assertEquals(15, $a->wgdof);
    }

    public function testGettingAnovaShouldSuccess()
    {
        $a = new Anova();
        $a->add(array(6, 8, 4, 5, 3, 4));
        $a->add(array(8, 12, 9, 11, 6, 8));
        $a->add(array(13, 9, 11, 8, 7, 12));

        $this->assertEquals((float) 9.3, (float) round($a->f(), 1));
        $this->assertEquals((float) 9.3, (float) round($a->fRatio(), 1));
        $this->assertEquals((float) 9.3, (float) round($a->f, 1));
        $this->assertEquals((float) 9.3, (float) round($a->f_ratio, 1));
    }
}
