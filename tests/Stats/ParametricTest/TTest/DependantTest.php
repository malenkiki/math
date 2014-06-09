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

use Malenki\Math\Stats\ParametricTest\TTest\Dependant;
use Malenki\Math\Stats\Stats;

class DependantTTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateStudentDependantsShouldSuccess()
    {
        $t = new Dependant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Dependant',
            $t
        );
    }

    public function testAddingTwoEqualSamplesShouldSuccess()
    {
        $t = new Dependant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Dependant',
            $t->add(new Stats(array(6, 3, 4)))
        );
        
        $t = new Dependant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Dependant',
            $t->add(array(6, 3, 4))->add(array(7, 5, 9))
        );
        
        
        $t = new Dependant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Dependant',
            $t->set(array(6, 3, 4), array(7, 5, 9))
        );
        
        $t = new Dependant();
        $this->assertInstanceOf(
            '\Malenki\Math\Stats\ParametricTest\TTest\Dependant',
            $t->set(new Stats(array(6, 3, 4)), new Stats(array(7, 5, 9)))
        );
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddingTwoNonEqualSamplesShouldFail()
    {
        $t = new Dependant();
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3, 4)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddingMoreThanTwoSamplesShouldFail()
    {
        $t = new Dependant();
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
        $t->add(new Stats(array(1, 2, 3)));
    }



    public function testGettingCountShouldSuccess()
    {
        $t = new Dependant();
        $t
            ->add(array(6, 3, 4, 7, 8, 9, 3, 6))
            ->add(array(6, 3, 4, 8, 8, 9, 3, 6));

        $this->assertCount(8, $t);
        $this->assertEquals(8, $t->count());
        $this->assertEquals(8, $t->count);
        $this->assertEquals(8, count($t));
    }

    public function testGettingDegreeOfFreedomShouldSuccess()
    {
        $t = new Dependant();
        $t->add(array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18));
        $t->add(array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22));
        $this->assertEquals(14, $t->degreesOfFreedom());
        $this->assertEquals(14, $t->dof());
        $this->assertEquals(14, $t->dof);
        $this->assertEquals(14, $t->degrees_of_freedom);
    }
    
    

    public function testGettingSigmaShouldSuccess()
    {
        $t = new Dependant();
        $t->add(array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18));
        $t->add(array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22));
        $this->assertEquals(0.608, round($t->sigma(), 3));
        $this->assertEquals(0.608, round($t->sigma, 3));
    }
    
    

    public function testGettingTShouldSuccess()
    {
        $t = new Dependant();
        $t->add(array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18));
        $t->add(array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22));
        $this->assertEquals(-4.054, round($t->t(), 3));
        $this->assertEquals(-4.054, round($t->t, 3));
    }
    
    

    /**
     * @expectedException \LogicException
     */
    public function testGettingTUsingTwoSameSamplesShouldFail()
    {
        $t = new Dependant();
        $t
            ->add(array(6, 3, 4, 7, 8, 9, 3, 6))
            ->add(array(6, 3, 4, 7, 8, 9, 3, 6));

        $t->t();
    }


    public function testSettingSamplesByMagicSettersShouldSuccess()
    {
        $arr1 = array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18);
        $arr2 = array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22);
        
        $s1 = new Stats($arr1);
        $s2 = new Stats($arr2);

        $t = new Dependant();
        $t->sample_one = $arr1;
        $t->sample_two = $arr2;
        $this->assertCount(15, $t);
        
        $t = new Dependant();
        $t->sample_1 = $arr1;
        $t->sample_2 = $arr2;
        $this->assertCount(15, $t);
        
        $t = new Dependant();
        $t->sample_a = $arr1;
        $t->sample_b = $arr2;
        $this->assertCount(15, $t);


        $t = new Dependant();
        $t->sample_one = $s1;
        $t->sample_two = $s2;
        $this->assertCount(15, $t);
        
        $t = new Dependant();
        $t->sample_1 = $s1;
        $t->sample_2 = $s2;
        $this->assertCount(15, $t);
        
        $t = new Dependant();
        $t->sample_a = $s1;
        $t->sample_b = $s2;
        $this->assertCount(15, $t);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettingBadTypeSamplesByMagicSettersShouldFail()
    {
        $t = new Dependant();
        $t->sample_one = (object) array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18);
        $t->sample_two = (object) array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22);
        $this->assertCount(15, $t);
    }

}
