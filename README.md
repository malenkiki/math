# Math

Little library to deal with some mathematical stuff.

Implemented or partially implemented are: Complex number, Matrix, Normal distribution, Random, Angle.

## Angle

You can use angles as **deg**, **gon**, **rad** or **turn**. By default, radians is used.

```php
$a = new \Malenki\Math\Angle(pi()/2);
var_dump($a->deg); // get degrees
var_dump($a->rad); // get radians
var_dump($a->gon); // get radians
var_dump($a->turn); // get turns
```

You can get DMS style too:

```php
$a = new \Malenki\Math\Angle(34.53, \Malenki\Math\Angle::TYPE_DEG);
var_dump($a->dms); // get DMS object
var_dump($a->dms->str); // get DMS string '34°31′48″'
```

You can test whether current angle is **right**, **straight** or **perigon**:

```php
$a = new \Malenki\Math\Angle(pi() / 2);
var_dump($a->isRight()); // should return TRUE
$b = new \Malenki\Math\Angle(pi());
var_dump($b->isStraight()); // should return TRUE
$c = new \Malenki\Math\Angle(2 * pi());
var_dump($c->isPerigon()); // should return TRUE
$d = new \Malenki\Math\Angle(450, \Malenki\Math\Angle::TYPE_DEG); //yes, ignore multiple turns :)
var_dump($d->isRight()); // should return TRUE
```

You can test current angle with another to know is they are **complementary** or **supplementary**:

```php
$a = new \Malenki\Math\Angle(pi() / 3);
$b = new \Malenki\Math\Angle(pi() / 6);
var_dump($a->isComplementary($b)); // should be TRUE
var_dump($b->isComplementary($a)); // should be TRUE

$c = new \Malenki\Math\Angle(pi() / 2);
$d = new \Malenki\Math\Angle(90, \Malenki\Math\Angle::TYPE_DEG);
var_dump($c->isSupplementary($d)); // should be TRUE
var_dump($d->isSupplementary($c)); // should be TRUE
```

## Matrix

Creating matrix is simple, first step, you instanciate it with column numbers and row numbers, second step, you put data into it.

```php
//instanciate
$m = new \Malenki\Math\Matrix(3, 2);
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

Getting matrix __transpose__ is as simple as that:

```php
echo $m->transpose();
```

OK, you can get, you can set… but you can do more complicated things.

You can __multiply__ matrix with another one, __but beware of compatibility!__

```php
$n = new \Malenki\Math\Matrix(2, 3);
$n->populate(array(7, 8, 9, 10, 11, 12));

if($m->multiplyAllow($n))
{
    print($m->multiply($n));
}
```

You can multiply matrix with a scalar too, or a complex number:

```php
$z = new \Malenki\Math\Complex(2, -3);
$n->multiply(2);
$n->multiply($z);
```

__Addition__ is possible too, you must test before if size of each matrix is the same, or catch exception.

```php
try {
    echo $m->add($n);
}
catch(\Exception $e)
{
    echo $e->getMessage();
}

//or

if($m->sameSize($n))
{
    echo $m->add($n);
}
else
{
    echo "Cannot add M to N: not the same size!";
}
```

Getting __determinant__ of a square matrix is easy, just do the following:

```php
$m = new \Malenki\Math\Matrix(2,2);
$m->populate(array(1,2,3,4));
var_dump($m->det()); // should be -2
```

If you try to get determinant for a non square matrix, you get an Exception.

__Inverse__ of square matrix is simple too, and like you can imagine, it is like that:

```php
$m = new \Malenki\Math\Matrix(2,2);
$m->populate(array(1,2,3,4));
$i = $m->inverse();
echo $i;
// should be:
// -2   1
// 1.5  -0.5
```
The __cofactor matrix__, used be previous method, is compute with that:

```php
$m = new \Malenki\Math\Matrix(2,2);
$m->populate(array(1,2,3,4));
$c = $m->cofactor();
echo $c;
```


## Complex

Using complex is like a child game: instanciate it with real part and imaginary part. That’s all!

```php
$z = new \Malenki\Math\Complex(2, -3);
```

But you can create complex number using __rho__ and __theta__ values, theta can be simple float or **Angle object**:

```php
use \Malenki\Math\Complex;
use \Malenki\Math\Angle;

$z = new Complex(1, pi(), Complex::TRIGONOMETRIC);
// or
$a = new Angle(M_PI);
$z = new Complex(1, $a); // 3rd argument is useless if Angle is used as second argumeent
```

Complex number object acts like string too:

```php
echo $z; // print 2-3i
```

You have some magic getters:

```php
$z = new \Malenki\Math\Complex(1,2);
var_dump($z->real); // real part
var_dump($z->re); // real part
var_dump($z->r); // real part
var_dump($z->imaginary); // imaginary part
var_dump($z->im); //imaginary part 
var_dump($z->i); // imaginary part
var_dump($z->rho); // modulus aka norm
var_dump($z->theta); // argument (angle)
```

You can do addition, multiplication:

```php
$z = new \Malenki\Math\Complex(1,2);
$zz = new \Malenki\Math\Complex(2,3);
echo $z->add($zz); // give new complex nulber
echo $z->multiply($zz); // give another complex number
```

Get negative and conjugate is simple too:

```php
$z = new \Malenki\Math\Complex(1,2);
echo $z->conjugate();
echo $z->negative();
```


## Normal distribution

You can play with graph for given mean and standard deviation, or you can generate fake samples.

Some examples to understand:

```php
// Normal Distribution with mean equals to 2 and has standard deviation of 0.3
$nd = new \Malenki\Math\NormalDistribution(2, 0.3);
// you can get value of function:
$nd->f(3);
// you can generate fake sample following the current normal distribution:
$md->samples(100); // 100 elements into an array
```

## Random

You can play with random numbers, included into integer range or as float between 0 and 1.

You can take one:

```php
$r = new \Malenki\Math\Random(); // double form 0 to 1 only
var_dump($r->get());

$r = new \Malenki\Math\Random(-5, 18); // integer range
var_dump($r->get());
```

You can take many:

```php
$r = new \Malenki\Math\Random(); // double form 0 to 1 only
var_dump($r->getMany(5));

$r = new \Malenki\Math\Random(-5, 18); // integer range
var_dump($r->getMany(5));
```

You can take many without replacement:

```php
$r = new \Malenki\Math\Random(); // double form 0 to 1 only
var_dump($r->getManyWithoutReplacement(5));

$r = new \Malenki\Math\Random(-5, 18); // integer range
var_dump($r->getManyWithoutReplacement(5));
```
