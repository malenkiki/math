<?php
/*
 * Copyright (c) 2014 Michel Petit <petit.michel@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


namespace Malenki\Math;

/**
 * Matrix basic implementation.
 *
 * Different ways are available to instanciate a Matrix:
 *
 * - by setting all values using one dimensional array,
 * - by adding columns,
 * - by adding rows
 *
 * So, a little example to show you:
 *
 *     $m = new Matrix(2, 3); // 2 rows, 3 columns
 *
 *     $m->populate(array(1, 2, 3, 4, 5, 6)); // populate into one shot
 *     
 *     // or adding columns
 *     $m->addCol(array(1, 4));
 *     $m->addCol(array(2, 5));
 *     $m->addCol(array(3, 6));
 *     
 *     // or adding rows
 *     $m->addRow(array(1, 2, 3));
 *     $m->addRow(array(4, 5, 6));
 *
 *
 *  Some actions are available, like __transpose__, __multiply__ with scalar or Matrix, 
 *  __add__ Matrix, test if a Matrix is square… See methods to have more information!
 * 
 * @todo as a reminder: http://www.latp.univ-mrs.fr/~torresan/CalcMat/cours/node2.html
 *
 * @property-read $cols Amount of columns
 * @property-read $rows Amount of rows
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Matrix
{
    /**
     * Items of the matrix, as an array of array. 
     * 
     * @var array
     * @access protected
     */
    protected $arr = array();

    /**
     * Size of the matrix: column and row number. 
     * 
     * @var mixed
     * @access protected
     */
    protected $size = null;



    /**
     * Defines magic getter to acces to column's numbers and row's numbers 
     * 
     * @param mixed $name 
     * @access public
     * @return integer
     */
    public function __get($name)
    {
        if(in_array($name, array('cols', 'rows')))
        {
            return $this->size->$name;
        }

        if($name == 'array')
        {
            return $this->arr;
        }

        if($name == 'is_square')
        {
            return $this->isSquare();
        }

        if(in_array($name, array('cofactor', 'adjugate', 'inverse', 'det', 'trace', 'determinant', 'transpose')))
        {
            return $this->$name();
        }
    }



    /**
     * Constructs new matrix giving its size.
     * 
     * @throw \InvalidArgumentException If numbers of cols or rows are not integers.
     * @throw \InvalidArgumentException If numbers of cols or rows are not positive numbers.
     * @param integer $int_rows 
     * @param integer $int_cols 
     * @access public
     * @return Matrix
     */
    public function __construct($int_rows, $int_cols)
    {
        if(!is_numeric($int_cols) || !is_numeric($int_rows))
        {
            throw new \InvalidArgumentException('number of cols and rows must be integers.');
        }

        $int_cols = (integer) $int_cols;
        $int_rows = (integer) $int_rows;

        if($int_cols <= 0 || $int_rows <= 0)
        {
            throw new \InvalidArgumentException('Number of cols and rows must be positive not null integers.');
        }

        $this->size = new \stdClass();
        $this->size->cols = $int_cols;
        $this->size->rows = $int_rows;
    }



    /**
     * Puts all values into the matrix. 
     * 
     * Argument is a simple array of one dimension. If the matrix has 3 columns 
     * and 2 rows, the the argument must have 6 elements. Under the hood, the 
     * given array is split to fit the matrix.
     *
     * By the way, you can use rows and colunms in place of this method.
     *
     * @param array $arrAll 
     * @access public
     * @return Matrix
     */
    public function populate($arrAll)
    {
        $this->arr = array_chunk($arrAll, $this->size->cols);

        return $this;
    }



    /**
     * Gets one item at given row and column. 
     * 
     * @throw \InvalidArgumentException If indexes are not integers
     * @throw \InvalidArgumentException If one of the given indexes does not exist.
     * @param integer $int_i Row index
     * @param integer $int_j Column index
     * @access public
     * @return float
     */
    public function get($int_i, $int_j)
    {
        if(!is_integer($int_i) || !is_integer($int_j))
        {
            throw new \InvalidArgumentException('Indices must be integers.');
        }

        if(
            $int_i >= $this->size->rows
            ||
            $int_j >= $this->size->cols
            ||
            $int_i < 0
            ||
            $int_j < 0
        )
        {
            throw new \InvalidArgumentException('Row’s index or column’s index does not exist!');
        }
        return $this->arr[$int_i][$int_j];
    }



    /**
     * Adds a row to populate step by step the matrix
     * 
     * @throw \OutOfRangeException If this is called and the number of rows is full.
     * @throw \InvalidArgumentException If row has not the same number of column that previous one.
     * @param array $arr_row Data to add
     * @access public
     * @return Matrix
     */
    public function addRow(array $arr_row)
    {
        if(count($this->arr) == $this->size->rows)
        {
            throw new \OutOfRangeException(sprintf('You cannot add another row! Max number of rows is %d', $this->size->rows));
        }

        if(count($arr_row) != $this->size->cols)
        {
            throw new \InvalidArgumentException('New row must have same amout of columns than defined into the size matrix');
        }

        $this->arr[] = $arr_row;

        return $this;
    }



    /**
     * Adds a column to populate step by step the matrix
     * 
     * @throw \OutOfRangeException If this is called and the number of columns is full.
     * @throw \InvalidArgumentException If colmun has not the same number of rows that previous one.
     * @param array $arr_col Data to add
     * @access public
     * @return Matrix
     */
    public function addCol($arr_col)
    {
        if(isset($this->arr[0]) && (count($this->arr[0]) == $this->size->cols))
        {
            throw new \OutOfRangeException(sprintf('You cannot add another column! Max number of columns is %d', $this->size->cols));
        }

        if(count($arr_col) != $this->size->rows)
        {
            throw new \InvalidArgumentException('New column must have same amout of rows than previous columns.');
        }

        $arr_col = array_values($arr_col); //to be sure to have index 0, 1, 2…

        foreach($arr_col as $k => $v)
        {
            $this->arr[$k][] = $arr_col[$k];
        }


        return $this;
    }



    /**
     * Gets the row having the given index.
     * 
     * @throw \OutOfRangeException If given index does not exist.
     * @param integer $int Row's index
     * @access public
     * @return mixed
     */
    public function getRow($int = 0)
    {
        if(!isset($this->arr[$int]))
        {
            throw new \OutOfRangeException('There is no line having this index.');
        }

        return $this->arr[$int];
    }



    /**
     * Gets the column having the given index.
     * 
     * @throw \OutOfRangeException If given index does not exist.
     * @param integer $int column's index 
     * @access public
     * @return mixed
     */
    public function getCol($int = 0)
    {
        if($int >= $this->size->cols)
        {
            throw new \OutOfRangeException('There is not column having this index.');
        }

        $arr_out = array();

        foreach($this->arr as $row)
        {
            $arr_out[] = $row[$int];
        }

        return $arr_out;
    }



    /**
     * Tells whether the current matrix is square or not. 
     * 
     * @access public
     * @return boolean
     */
    public function isSquare()
    {
        return $this->size->cols == $this->size->rows;
    }



    /**
     * Tests whether the current matrix is the same as the given one.
     * 
     * @param Matrix $matrix 
     * @access public
     * @return boolean
     */
    public function sameSize($matrix)
    {
        return (
            $this->size->cols == $matrix->cols
            &&
            $this->size->rows == $matrix->rows
        );
    }



    /**
     * Tests whether the current matrix can be multiply with the given one.
     *
     * @param mixed $matrix Scalar, complex or matrix
     * @access public
     * @return boolean
     */
    public function multiplyAllow($matrix)
    {
        if(is_numeric($matrix))
        {
            return true;
        }
        
        if($matrix instanceof \Malenki\Math\Complex)
        {
            return true;
        }

        if($matrix instanceof \Malenki\Math\Matrix)
        {
            return $this->size->cols == $matrix->rows;
        }

        return false;
    }



    /**
     * Returns the transpose of the current matrix.
     * 
     * @access public
     * @return Matrix
     */
    public function transpose()
    {
        $out = new self($this->size->cols, $this->size->rows);

        foreach($this->arr as $row)
        {
            $out->addCol($row);
        }

        return $out;
    }



    /**
     * Adds the given matrix with the current one to give another new matrix. 
     * 
     * @throw \InvalidArgumentException If argument is not a Matrix
     * @throw \RuntimeException If given matrix has not the same size.
     * @param Matrix $matrix Matrix to add
     * @access public
     * @return Matrix
     */
    public function add($matrix)
    {
        if(!($matrix instanceof \Malenki\Math\Matrix))
        {
            throw new \InvalidArgumentException('Given argument must be an instance of \Malenki\Math\Matrix');
        }

        if(!$this->sameSize($matrix))
        {
            throw new \RuntimeException('Cannot adding given matrix: it has wrong size.');
        }

        $out = new self($this->size->rows, $this->size->cols);

        foreach($this->arr as $k => $v)
        {
            $arrOther = $matrix->getRow($k);
            $arrNew = array();

            foreach($v as $kk => $vv)
            {
                if($arrOther[$kk] instanceof Complex)
                {
                    $arrNew[] = $arrOther[$kk]->add($vv);
                }
                elseif($vv instanceof Complex)
                {
                    $arrNew[] = $vv->add($arrOther[$kk]);
                }
                else
                {
                    $arrNew[] = $arrOther[$kk] + $vv;
                }
            }

            $out->addRow($arrNew);
        }

        return $out;
    }



    /**
     * Multiplies current matrix to another one or to a scalar. 
     * 
     * @throw \RuntimeException If argument is not valid number or if the given 
     * matrix has not the right numbers of rows.
     * @param mixed $mix Number (Real or Complex) or Matrix
     * @access public
     * @return Matrix
     */
    public function multiply($mix)
    {
        if(!$this->multiplyAllow($mix))
        {
            throw new \RuntimeException('Invalid number or matrix has not right number of rows.');
        }


        if($mix instanceof \Malenki\Math\Matrix)
        {
            $out = new self($this->size->rows, $mix->cols);

            for($r = 0; $r < $this->size->rows; $r++)
            {
                $arrOutRow = array();

                for($c = 0; $c < $mix->cols; $c++)
                {
                    $arrCol = $mix->getCol($c);
                    $arrRow = $this->getRow($r);

                    $arrItem = array();
                    $hasComplex = false;

                    foreach($arrCol as $k => $v)
                    {
                        if($arrRow[$k] instanceof Complex)
                        {
                            $arrItem[] = $arrRow[$k]->multiply($v);
                            $hasComplex = true;
                        }
                        elseif($v instanceof Complex)
                        {
                            $arrItem[] = $v->multiply($arrRow[$k]);
                            $hasComplex = true;
                        }
                        else
                        {
                            $arrItem[] = $arrRow[$k] * $v;
                        }
                    }

                    if($hasComplex)
                    {
                        $sum = new Complex(0, 0);

                        foreach($arrItem as $item)
                        {
                            if(is_numeric($item))
                            {
                                $item = new Complex($item, 0);
                            }

                            $sum = $item->add($sum);
                        }

                        $arrOutRow[] = $sum;
                    }
                    else
                    {
                        $arrOutRow[] = array_sum($arrItem);
                    }
                }

                $out->addRow($arrOutRow);
            }

            return $out;
        }

        if(is_numeric($mix) || $mix instanceof Complex)
        {
            $out = new self($this->size->rows, $this->size->cols);

            for($r = 0; $r < $this->size->rows; $r++)
            {
                $arrRow = $this->getRow($r);

                foreach($arrRow as $k => $v)
                {
                    if(is_numeric($mix))
                    {
                        if($v instanceof Complex)
                        {
                            $arrRow[$k] = $v->multiply($mix);
                        }
                        else
                        {
                            $arrRow[$k] = $mix * $v;
                        }
                    }
                    else
                    {
                        $arrRow[$k] = $mix->multiply($v);
                    }
                }

                $out->addRow($arrRow);
            }

            return $out;
        }
    }


    /**
     * Gets the cofactor matrix
     * 
     * @access public
     * @return Matrix
     */
    public function cofactor()
    {
        $c = new self($this->size->rows, $this->size->cols);


        for($m = 0; $m < $this->size->rows; $m++)
        {
            $arr_row = array();

            for($n = 0; $n < $this->size->cols; $n++)
            {
                if($this->size->cols == 2)
                {
                    $arr_row[] = pow(-1, $m + $n) * $this->subMatrix($m, $n)->get(0,0);
                }
                else
                {
                    $arr_row[] = pow(-1, $m + $n) * $this->subMatrix($m, $n)->det();
                }
            }

            $c->addRow($arr_row);
        }

        return $c;
    }



    public function adjugate()
    {
        return $this->cofactor()->transpose();
    }



    /**
     * Gets inverse matrix.
     *
     * Inverse matrix is get if and only if the determinant is not null.
     *
     * @throw \RuntimeException If determinant is null.
     * @access public
     * @return Matrix
     */
    public function inverse()
    {
        $det = $this->det();

        if($det == 0)
        {
            throw new \RuntimeException('Cannot get inverse matrix: determinant is nul!');
        }

        return $this->adjugate()->multiply(1 / $det);
    }



    /**
     * Gets sub matrix from current. 
     * 
     * This is usefull for determinant calculus, inverse, etc.
     *
     * @param integer $int_m Index of rows to ignore
     * @param integer $int_n Index of column to ignore
     * @access public
     * @return Matrix
     */
    public function subMatrix($int_m, $int_n)
    {
        $sm = new self($this->size->rows - 1, $this->size->cols - 1);

        foreach($this->arr as $m => $row)
        {
            if($m != $int_m)
            {
                $arr_row = array();

                foreach($row as $n => $v)
                {
                    if($n != $int_n)
                    {
                        $arr_row[] = $v;
                    }
                }

                $sm->addRow($arr_row);
            }
        }

        return $sm;
    }



    /**
     * Computes the determinant. 
     * 
     * @todo What about complex number?
     * @throw \RuntimeException If matrix is not square.
     * @access public
     * @return float
     */
    public function det()
    {
        if(!$this->isSquare())
        {
            throw new \RuntimeException('Cannot compute determinant of non square matrix!');
        }

        if($this->size->rows == 2)
        {
            return $this->get(0,0) * $this->get(1,1) - $this->get(0,1) * $this->get(1,0);
        }
        else
        {
            $int_out = 0;

            $arr_row = $this->arr[0];

            foreach($arr_row as $n => $v)
            {
                $int_out += pow(-1, $n + 2) * $v * $this->subMatrix(0, $n)->det();
            }

            return $int_out;
        }
    }

    public function trace()
    {
        if(!$this->isSquare())
        {
            throw new \RuntimeException('Cannot compute trace of non square matrix!');
        }

        $int = 0;

        for($i = 0; $i < $this->size->rows; $i++)
        {
            $int += $this->get($i, $i);
        }

        return $int;
    }


    public function determinant()
    {
        return $this->det();
    }

        


    /**
     * Gets all data of the current matrix as 2 dimensions array
     * 
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->arr;
    }



    /**
     * Returns human readable matrix string like a pseudo table.
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        $arr_out = array();
        $arr_col_width = array();

        foreach($this->arr as $row)
        {
            $arr_row = array();

            foreach($row as $k => $item)
            {
                $arr_row[] = (string) $item;

                $int_length = strlen($arr_row[count($arr_row) - 1]);

                if(
                    (isset($arr_col_width[$k]) && $arr_col_width[$k] < $int_length)
                    ||
                    !isset($arr_col_width[$k])
                )
                {
                    $arr_col_width[$k] = $int_length;
                }
            }

            $arr_out[] = $arr_row;
        }

        foreach($arr_out as $idx => $row)
        {
            $arr_row = array();

            foreach($row as $k => $item)
            {
                $arr_row[] = str_pad($item, $arr_col_width[$k], ' ' ,STR_PAD_LEFT);
            }

            $arr_out[$idx] = implode('  ', $arr_row);
        }


        return implode(PHP_EOL, $arr_out);
    }
}
