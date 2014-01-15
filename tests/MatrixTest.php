<?php
/*
Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>

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



class MatrixTest extends PHPUnit_Framework_TestCase
{
    public function testPopulateOK()
    {
        $m = new Malenki\Math\Matrix(2, 3);
        $m->populate(array(1, 2, 3, 4, 5, 6));

        $this->assertEquals(array(1, 2, 3), $m->getRow(0));
        $this->assertEquals(array(4, 5, 6), $m->getRow(1));
        
        $m = new Malenki\Math\Matrix(2, 3);
        $m->addRow(array(1, 2, 3));
        $m->addRow(array(4, 5, 6));

        $this->assertEquals(array(1, 2, 3), $m->getRow(0));
        $this->assertEquals(array(4, 5, 6), $m->getRow(1));
    }


    public function testDetOK()
    {
        $m = new Malenki\Math\Matrix(2, 2);
        $m->populate(array(1, 2, 3, 4));

        $this->assertEquals(-2, $m->det());
        
        $m = new Malenki\Math\Matrix(3, 3);
        $m->populate(array(0,2,3,4,5,6,0,8,9));

        $this->assertEquals(24, $m->det());
    }
}
