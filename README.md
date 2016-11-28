# Math

[![Build Status](https://travis-ci.org/malenkiki/math.svg?branch=master)](https://travis-ci.org/malenkiki/math)

Library to deal with some mathematical stuff.

Implemented or partially implemented mathematical concepts are: **Complex number**, **Matrix**, **Normal distribution**, **Random**, **Angle**, **Random Complex**, **Descriptive Statistics**, **Parametric tests** (_Anova_, _Dependant t-Test_) and **Non-Parametric tests** (_Wilcoxon Signed-rank test_, _Wilcoxon-Mann-Whitney test_, _Kruskal-Wallis_).

## Install

You can get this lib by downloading the [ZIP archive](https://github.com/malenkiki/math/archive/master.zip), cloning this repository or using [Composer](https://getcomposer.org/) with the following code to put into your `composer.json` file:

```json
{
"require": {"malenki/math": "dev-master"}
}
```


## Angle

You can use angles as **deg**, **gon**, **rad** or **turn**. By default, radians is used.

```php
use \Malenki\Math\Unit\Angle;

$a = new Angle(pi()/2);
var_dump($a->deg); // get degrees
var_dump($a->rad); // get radians
var_dump($a->gon); // get radians
var_dump($a->turn); // get turns
```

You can get DMS style too:

```php
use \Malenki\Math\Unit\Angle;

$a = new Angle(34.53, Angle::TYPE_DEG);
var_dump($a->dms); // get DMS object
var_dump($a->dms->str); // get DMS string '34°31′48″'
```

You can test whether current angle is **right**, **straight** or **perigon**:

```php
use \Malenki\Math\Unit\Angle;

$a = new Angle(pi() / 2);
var_dump($a->isRight()); // should return TRUE

$b = new Angle(pi());
var_dump($b->isStraight()); // should return TRUE

$c = new Angle(2 * M_PI);
var_dump($c->isPerigon()); // should return TRUE

$d = new Angle(450, Angle::TYPE_DEG); //yes, ignore multiple turns :)
var_dump($d->isRight()); // should return TRUE
```

You can test current angle with another to know is they are **complementary** or **supplementary**:

```php
use \Malenki\Math\Unit\Angle;

$a = new Angle(M_PI / 3);
$b = new Angle(M_PI / 6);
var_dump($a->isComplementary($b)); // should be TRUE
var_dump($b->isComplementary($a)); // should be TRUE

$c = new Angle(M_PI / 2);
$d = new Angle(90, Angle::TYPE_DEG);
var_dump($c->isSupplementary($d)); // should be TRUE
var_dump($d->isSupplementary($c)); // should be TRUE
```

## Matrix

Creating matrix is simple, first step, you instanciate it with column numbers and row numbers, second step, you put data into it.

```php
use \Malenki\Math\Matrix;

//instanciate
$m = new Matrix(3, 2);
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
use \Malenki\Math\Matrix;

$n = new Matrix(2, 3);
$n->populate(array(7, 8, 9, 10, 11, 12));

if($m->multiplyAllow($n))
{
    print($m->multiply($n));
}
```

You can multiply matrix with a scalar too, or a complex number:

```php
use \Malenki\Math\Number\Complex;

$z = new Complex(2, -3);
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
use \Malenki\Math\Matrix;

$m = new Matrix(2,2);
$m->populate(array(1,2,3,4));
var_dump($m->det()); // should be -2
```

If you try to get determinant for a non square matrix, you get an Exception.

__Inverse__ of square matrix is simple too, and like you can imagine, it is like that:

```php
use \Malenki\Math\Matrix;

$m = new Matrix(2,2);
$m->populate(array(1,2,3,4));
$i = $m->inverse();
echo $i;
// should be:
// -2   1
// 1.5  -0.5
```
The __cofactor matrix__, used be previous method, is compute with that:

```php
use \Malenki\Math\Matrix;

$m = new Matrix(2,2);
$m->populate(array(1,2,3,4));
$c = $m->cofactor();
echo $c;
```


## Complex

Using complex is like a child game: instanciate it with real part and imaginary part. That’s all!

```php
use \Malenki\Math\Number\Complex;

$z = new Complex(2, -3);
```

But you can create complex number using __rho__ and __theta__ values, theta can be simple float or **Angle object**:

```php
use \Malenki\Math\Number\Complex;
use \Malenki\Math\Unit\Angle;

$z = new Complex(1, pi(), Complex::TRIGONOMETRIC);
// or
$a = new Angle(M_PI);
$z = new Complex(1, $a); // 3rd argument is useless if Angle is used as second argumeent
```

Complex number object acts like string too, remembering its original form:

```php
use \Malenki\Math\Number\Complex;
use \Malenki\Math\Unit\Angle;

$z = new Complex(2, -3);
echo $z; // print "2-3i"
$zz = new Complex(1, new Angle(3 * M_PI / 2));
echo $zz; // print "cos 4.712389 + i⋅sin 4.712389"
```

You have some magic getters:

```php
use \Malenki\Math\Number\Complex;

$z = new Complex(1,2);
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
use \Malenki\Math\Number\Complex;

$z = new Complex(1,2);
$zz = new Complex(2,3);
echo $z->add($zz); // give new complex nulber
echo $z->multiply($zz); // give another complex number
```

Get negative and conjugate is simple too:

```php
use \Malenki\Math\Number\Complex;

$z = new Complex(1,2);
echo $z->conjugate();
echo $z->negative();
```


## Normal distribution

You can play with graph for given mean and standard deviation, or you can generate fake samples.

Some examples to understand:

```php
use \Malenki\Math\Stats\NormalDistribution;

// Normal Distribution with mean equals to 2 and has standard deviation of 0.3
$nd = new NormalDistribution(2, 0.3);

// you can get value of function:
$nd->f(3);

// you can generate fake sample following the current normal distribution:
$nd->samples(100); // 100 elements into an array
```

## Factorial

You can get factorial of one integer, it is very easy to use, instanciate it with `n` rank and then get value by calling `result` attribute.

```php
use \Malenki\Math\Factorial;

$f = new Factorial(5);
$f->result; // should be 120
$f->n; // you can get rank as reminder too.
```

String context is available too:

```php
use \Malenki\Math\Factorial;

$f = new Factorial(5);
echo $f; // string '120'
```

## Random

You can play with random numbers, included into integer range or as float between 0 and 1.

You can take one:

```php
use \Malenki\Math\Random;

$r = new Random(); // double form 0 to 1 only
var_dump($r->get());

$r = new Random(-5, 18); // integer range
var_dump($r->get());
```

You can take many:

```php
use \Malenki\Math\Random;

$r = new Random(); // double form 0 to 1 only
var_dump($r->getMany(5));

$r = new Random(-5, 18); // integer range
var_dump($r->getMany(5));
```

You can take many without replacement:

```php
use \Malenki\Math\Random;

$r = new Random(); // double form 0 to 1 only
var_dump($r->getManyWithoutReplacement(5));

$r = new Random(-5, 18); // integer range
var_dump($r->getManyWithoutReplacement(5));
```

## Random Complex Number

This class allows you to get one or many complex numbers with real and 
imaginary or rho and theta inside given range.

So, to get one complex number with its real part into `[2, 6.5]` range and 
its imaginary part into `[-2, 5]` range, you must do that:

```php
use \Malenki\Math\RandomComplex;

$rc = new RandomComplex();
$rc->r(2, 6.5)->i(-2, 5)->get();
```

You can do that with trigonometric form too, but now with 10 generated 
items:

```php
use \Malenki\Math\RandomComplex;

$rc = new RandomComplex();
$rc->rho(1, 5)->theta(M_PI / 4, M_PI /2)->getMany(10);
```

## Descriptive Statistics

You can do a lot of stats about data, like **mean**, **variance**, **standard deviation**, **kurtosis**, etc.

You can put all values at once while instanciating:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));
```

You can add others data after too:

```php
$s->merge(array(8,4,6,3)); // to add several values
$s->add(5); // to add one by one value
```

Counting values is as easy to use `count()`:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));
var_dump(count($s));
```

Many means are avaialble too:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));

// arithmetic mean
var_dump($s->arithmeticMean());
var_dump($s->arithmetic_mean);
var_dump($s->mean);
var_dump($s->A);
var_dump($s->mu);

// harmonic mean
var_dump($s->harmonicMean());
var_dump($s->harmonic_mean);
var_dump($s->subcontrary_mean);
var_dump($s->H);

// geometric mean
var_dump($s->geometricMean());
var_dump($s->geometric_mean);
var_dump($s->G);

// root mean square aka RMS
var_dump($s->rootMeanSquare());
var_dump($s->root_mean_square);
var_dump($s->rms);
var_dump($s->quadratic_mean);
var_dump($s->Q);
```

Variance, population or sample are available with standard deviation too:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));

// Variance (population)
var_dump($s->variance());
var_dump($s->var);
var_dump($s->variance);
var_dump($s->population_variance);

// Variance (sample)
var_dump($s->sampleVariance());
var_dump($s->sample_variance);
var_dump($s->s2);

// Standard deviation
var_dump($s->standardDeviation());
var_dump($s->standard_deviation);
var_dump($s->stdev);
var_dump($s->stddev);
var_dump($s->sigma);
```
Quartiles and median:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));


var_dump($s->quartile(1));

var_dump($s->quartile(2));
//or
var_dump($s->mediane());

var_dump($s->quartile(3));

// or just by magic getters:

var_dump($s->first_quartile);
var_dump($s->second_quartile);
var_dump($s->third_quartile);
var_dump($s->last_quartile);
var_dump($s->mediane);
```

Getting mode(s):
```php
$s = new Stats(array(1,2,3,2,4,1,5));
var_dump($s->mode); // returned array has 2 values

$s = new Stats(array(1,3,2,4,1,5));
var_dump($s->mode); // returned array has 1 value
```

Frequencies, relative frequencies and cumulative frequencies:

```php
var_dump($s->frequency);
var_dump($s->relative_frequency);
var_dump($s->cumulative_frequency);
// or methods
var_dump($s->frequency());
var_dump($s->relativeFrequency());
var_dump($s->cumulativeFrequency());
```

Kurtosis and its tests are available:

```php
use \Malenki\Math\Stats\Stats;
$s = new Stats(array(1,2,4,2,6,4));

var_dump($s->kurtosis);
var_dump($s->is_platykurtic);
var_dump($s->is_leptokurtic);
var_dump($s->is_mesokurtic);

// or method way:
var_dump($s->kurtosis());
var_dump($s->isPlatykurtic());
var_dump($s->isLeptokurtic());
var_dump($s->isMesokurtic());
```
## Parametric Tests

### Anova

One example is better than long blahblah:

```php
use Malenki\Math\Stats\ParametricTest\Anova;
$a = new Anova();
$a->add(array(6, 8, 4, 5, 3, 4));
$a->add(array(8, 12, 9, 11, 6, 8));
$a->add(array(13, 9, 11, 8, 7, 12));

// degrees of freedom
echo $a->degrees_of_freedom;
echo $a->dof;
echo $a->degreesOfFreedom();
echo $a->dof();

// Within group degrees of freedom
echo $a->within_group_degrees_of_freedom;
echo $a->WithinGroupDegreesOfFreedom();
echo $a->wgdof();

echo $a->f;
//or
echo $a->f_ratio;
//or
echo $a->f();
//or
echo $a->fRatio();
// should be around 9.3
```

### Dependant t-Test

```php
use Malenki\Math\Stats\ParametricTest\TTest;
$t = new Dependant();
$t->add(array(24, 17, 32, 14, 16, 22, 26, 19, 19, 22, 21, 25, 16, 24, 18));
$t->add(array(26, 24, 31, 17, 17, 25, 25, 24, 22, 23, 26, 28, 19, 23, 22));

// Degree Of Freedom
echo $t->dof(); // should be 14

// Sigma, the standard deviation
echo $t->sigma(); // Should be around 0.608

// The t-value
echo $t->t(); // Should be around -4.054
```

## Non Parametric Tests

### Wilcoxon Signed-Ranks Test

TODO

Dev in progress, more informations soon here on into source code!

## MIT Open Source License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
