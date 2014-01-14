# Math

Little library to deal with some mathematical stuff.

Implemented or partially implemented are: Complex number, Matrix and Normal distribution.

## Matrix

Creating matrix is simple, first step, you instanciate it with column numbers and row numbers, second step, you put data into it.

```php
//instanciate
$m = new \Malenki\Math\Matrix(2, 3);
//then populate
$m->populate(array(1,2,3,4,5,6));
//or
$m->addRow(array(1, 2));
$m->addRow(array(3, 4));
$m->addRow(array(5, 6));
//or
$m->addCol(array(1, 3, 5));
$m->addCol(array(2, 4, 6));
```

Getting data is not difficult too. You can get matrice’s size, content for a row or a column, all data and even a dump as a string.

```php
var_dump($m->cols); // amount of columns
var_dump($m->rows); // amount of rows

var_dump($m->getRow(0)); // get row having index 0
var_dump($m->getCol(1)); // get column having index 1

var_dump($m->getAll()); // Gets all data as array

// following will output that:
// 1  2
// 3  4
// 5  6
print($m);
```

OK, you can get, you can set… but you can do more complicated things:

TODO


## Complex

Using complex is like a child game: instanciate it with real part and imaginary part. That’s all!

```php
$z = new \Malenki\MAth\Complex(2, -3);
```

Complex number object acts like string too:

```php
echo $z; // print 2-3i
```

TODO

## Normal distribution

You can play with graph for given mean and standard deviation, or you can generate fake samples.

TODO
