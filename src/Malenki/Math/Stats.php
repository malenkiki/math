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

class Stats implements \Countable
{
    protected $arr = array();
    protected $int_count = null;

    protected $float_harmonic_mean = null;
    protected $float_geometric_mean = null;
    protected $float_arithmetic_mean = null;
    protected $float_root_mean_square = null;
    protected $float_range = null;
    protected $float_variance = null;
    protected $float_stddev = null;
    protected $float_sample_variance = null;
    protected $float_kurtosis = null;
    protected $float_median = null;
    protected $float_skew = null;
    protected $arr_mode = null;
    protected $arr_f = null;
    protected $arr_frequency = null;
    protected $arr_cumulative_frequency = null;

    public function __get($name)
    {
        if (in_array($name, array('is_unimodal', 'unimodal'))) {
            return $this->isUnimodal();
        }

        if (in_array($name, array('is_bimodal', 'bimodal'))) {
            return $this->isBimodal();
        }

        if (in_array($name, array('is_multimodal', 'multimodal'))) {
            return $this->isMultimodal();
        }

        if (in_array($name, array('harmonic_mean', 'subcontrary_mean', 'H'))) {
            return $this->harmonicMean();
        }

        if (in_array($name, array('geometric_mean', 'G'))) {
            return $this->geometricMean();
        }

        if (in_array($name, array('arithmetic_mean', 'mean', 'A', 'mu'))) {
            return $this->arithmeticMean();
        }

        if (in_array($name, array('root_mean_square', 'rms', 'quadratic_mean', 'Q'))) {
            return $this->rootMeanSquare();
        }

        if (in_array($name, array('heronian_mean', 'heronian'))) {
            return $this->heronianMean();
        }

        if (in_array($name, array('contraharmonic_mean', 'contraharmonic'))) {
            return $this->contraharmonicMean();
        }

        if (in_array($name, array('midrange', 'midextreme', 'mid_range', 'mid_extreme'))) {
            return $this->midrange();
        }

        if ($name == 'relative_frequency') {
            return $this->relativeFrequency();
        }

        if ($name == 'cumulative_frequency') {
            return $this->cumulativeFrequency();
        }

        if ($name == 'square_sum' || $name == 'sum2') {
            return $this->squareSum();
        }

        if (in_array($name, array('sum', 'median', 'array', 'min', 'max', 'mode', 'f', 'range'))) {
            return $this->$name();
        }

        if (in_array($name, array('variance', 'var', 'population_variance'))) {
            return $this->variance();
        }

        if (in_array($name, array('stdev', 'stddev', 'standard_deviation', 'sigma'))) {
            return $this->standardDeviation();
        }

        if (in_array($name, array('sample_variance', 's2'))) {
            return $this->sampleVariance();
        }

        if ($name == 'kurtosis') {
            return $this->kurtosis();
        }

        if ($name == 'is_platykurtic') {
            return $this->isPlatykurtic();
        }

        if ($name == 'is_leptokurtic') {
            return $this->isLeptokurtic();
        }

        if ($name == 'is_mesokurtic') {
            return $this->isMesokurtic();
        }

        if (preg_match('/(first|second|third|last)_quartile/', $name)) {
            $n = 1;

            if (preg_match('/^first/', $name)) {
                $n = 1;
            }
            if (preg_match('/^second/', $name)) {
                $n = 2;
            }
            if (preg_match('/^(third|last)/', $name)) {
                $n = 3;
            }

            return $this->quartile($n);
        }

        if(
            in_array(
                $name,
                array(
                    'interquartile_range',
                    'interquartile',
                    'iqr',
                    'IQR'
                )
            )
        )
        {
            return $this->interquartileRange();
        }

        if ($name == 'skew' || $name == 'skewness') {
            return $this->$name();
        }

        if(
            in_array(
                $name,
                array(
                    'is_left_skewed',
                    'left_skewed',
                    'is_negative_skew',
                    'negative_skew',
                    'is_left_tailed',
                    'left_tailed',
                    'skewed_to_the_left'
                )
            )
        )
        {
            return $this->isLeftSkewed();
        }

        if(
            in_array(
                $name,
                array(
                    'is_right_skewed',
                    'right_skewed',
                    'is_positive_skew',
                    'positive_skew',
                    'is_right_tailed',
                    'right_tailed',
                    'skewed_to_the_right'
                )
            )
        )
        {
            return $this->isRightSkewed();
        }

        if (in_array($name, array('frequency', 'freq'))) {
            return $this->frequency();
        }

        if (in_array($name, array('coefficient_of_variation', 'cv'))) {
            return $this->coefficientOfVariation();
        }

        if(
            in_array(
                $name,
                array(
                    'coefficient_of_dispersion',
                    'dispersion_index',
                    'coefficient_of_dispersion',
                    'variance_to_mean_ratio',
                    'vmr',
                    'VMR',
                    'D'
                )
            )
        )
        {
            return $this->indexOfDispersion();
        }

        if(
            in_array(
                $name,
                array(
                    'pearsons_rho',
                    'ppmcc',
                    'pcc',
                    'pearson_product_moment_correlation_coefficient'
                )
            )
        )
        {
            return $this->pearsonsR();
        }

    }

    public function __construct($arr = array())
    {
        $this->merge($arr);
    }

    public function get($idx)
    {
        if (!is_integer($idx)) {
            throw new \InvalidArgumentException('Index must be an integer');
        }

        if ($idx < 0) {
            throw new \InvalidArgumentException('Index must be null or positive integer!');
        }

        return $this->arr[$idx];
    }

    public function sum()
    {
        return array_sum($this->arr);
    }

    public function powerSum($p)
    {
        $arr = $this->arr;

        foreach($arr as $k => $v) {
            $arr[$k] = pow($v, $p);
        }

        return array_sum($arr);
    }

    public function squareSum()
    {
        return $this->powerSum(2);
    }

    public function min()
    {
        return min($this->arr);
    }

    public function max()
    {
        return max($this->arr);
    }

    public function count()
    {
        if (is_null($this->int_count)) {
            $this->int_count = count($this->arr);
        }

        return $this->int_count;
    }

    public function isEmpty()
    {
        return count($this) == 0;
    }

    public function allPositive()
    {
        for ($i = 0; $i < count($this); $i++) {
            if ($this->arr[$i] < 0) {
                return false;
            }
        }

        return true;
    }

    public function allInteger()
    {
        for ($i = 0; $i < count($this); $i++) {
            $n = $this->arr[$i];

            if (abs($n) - floor(abs($n)) != 0) {
                return false;
            }
        }

        return true;
    }

    protected function clear()
    {
        $this->int_count = null;
        $this->float_harmonic_mean = null;
        $this->float_geometric_mean = null;
        $this->float_arithmetic_mean = null;
        $this->float_root_mean_square = null;
        $this->float_range = null;
        $this->float_variance = null;
        $this->float_sample_variance = null;
        $this->float_stddev = null;
        $this->float_kurtosis = null;
        $this->float_median = null;
        $this->float_skew = null;
        $this->arr_mode = null;
        $this->arr_frequency = null;
        $this->arr_f = null;
        $this->arr_cumulative_frequency = null;
    }

    public function merge($arr)
    {
        if (!is_array($arr)) {
            throw new \InvalidArgumentException('Merging new values must be done with array!');
        }

        foreach ($arr as $k => $v) {
            if (!is_numeric($v)) {
                throw new \RuntimeException('Array to merge contains non numeric values!');
            }

            $arr[$k] = (double) $v;
        }

        $this->arr = array_merge($this->arr, $arr);
        $this->clear();

        return $this;
    }

    public function add($num)
    {
        if (!is_numeric($num)) {
            throw new \InvalidArgumentException('Only numeric values are allowed into statistical collection.');
        }
        $this->arr[] = (double) $num;
        $this->clear();

        return $this;
    }

    public function range()
    {
        if (is_null($this->float_range)) {
            $this->float_range = max($this->arr) - min($this->arr);
        }

        return $this->float_range;
    }

    /**
     * @todo Implement mode for continuous distribution, see http://en.wikipedia.org/wiki/Mode_(statistics)#Mode_of_a_sample
     */
    public function mode($inteval = null)
    {
        if (is_null($this->arr_mode)) {
            if ($this->allInteger()) {
                $arr = $this->frequency();
                $max = max($arr);
                $arr_out = array();

                foreach ($arr as $k => $v) {
                    if ($v == $max) {
                        $arr_out[] = (double) $k;
                    }
                }

                $this->arr_mode = $arr_out;
            } else {
                if (is_null($interval) || $interval <= 0) {
                    throw new \InvalidArgumentException('Interval for continuous distribution must be a valid positive number.');
                }
                throw new \RuntimeException('Mode for continuous distribution is not implemented yet.');
            }
        }

        return $this->arr_mode;
    }

    public function isUnimodal()
    {
        return count($this->mode()) == 1;
    }

    public function isBimodal()
    {
        return count($this->mode()) == 2;
    }

    public function isMultimodal()
    {
        return count($this->mode()) > 2;
    }

    public function arithmeticMean()
    {
        if (is_null($this->float_arithmetic_mean)) {
            $this->float_arithmetic_mean = array_sum($this->arr) / count($this);
        }

        return $this->float_arithmetic_mean;
    }

    public function mean()
    {
        return $this->arithmeticMean();
    }

    public function harmonicMean()
    {
        if (is_null($this->float_harmonic_mean)) {
            $arr = array();

            foreach ($this->arr as $v) {
                $arr[] = 1 / $v;
            }

            $this->float_harmonic_mean = count($this) / array_sum($arr);
        }

        return $this->float_harmonic_mean;
    }

    public function geometricMean()
    {
        if (is_null($this->float_geometric_mean)) {
            $this->float_geometric_mean = pow(
                array_product($this->arr),
                1 / count($this)
            );
        }

        return $this->float_geometric_mean;
    }

    public function rootMeanSquare()
    {
        if (is_null($this->float_root_mean_square)) {
            $s = new self(
                array_map(
                    function ($n) {
                        return $n * $n;
                    },
                    $this->arr
                )
            );

            $this->float_root_mean_square = sqrt($s->mean);
        }

        return $this->float_root_mean_square;
    }

    public function rms()
    {
        return $this->rootMeanSquare();
    }

    public function quadraticMean()
    {
        return $this->rootMeanSquare();
    }

    public function generalizedMean($p)
    {
        if ($p <= 0) {
            throw new \InvalidArgumentException('Generalized mean takes p as non-zero positive real number.');
        }

        if (!$this->allPositive()) {
            throw new \RuntimeException('Power mean use only collection of positive numbers!');
        }

        $arr = array();

        for ($i = 0; $i < count($this); $i++) {
            $arr[] = pow($this->arr[$i], $p);
        }

        return pow(array_sum($arr) / count($this), 1/$p);
    }

    public function powerMean($p)
    {
        return $this->generalizedMean($p);
    }

    public function heronianMean()
    {
        if (count($this) != 2) {
            throw new \RuntimeException('Heronian mean use only 2 numbers!');
        }

        if (!$this->allPositive()) {
            throw new \RuntimeException('Heronian mean is only possible on positive real numbers');
        }

        return ($this->arr[0] + sqrt(array_product($this->arr)) + $this->arr[1]) / 3;
    }

    public function heronian()
    {
        return $this->heronianMean();
    }

    public function lehmerMean($p)
    {
        if (!is_numeric($p)) {
            throw new \InvalidArgumentException('P must be a real number');
        }

        if (!$this->allPositive()) {
            throw new \RuntimeException('Lehmer mean is only possible on positive real numbers');
        }

        $top = 0;
        $bottom = 0;

        foreach ($this->arr as $v) {
            $top += pow($v, $p);
            $bottom += pow($v, $p - 1);
        }

        return $top / $bottom;
    }

    public function lehmer($p)
    {
        return $this->lehmerMean();
    }

    public function contraharmonicMean()
    {
        return $this->lehmerMean(2);
    }

    public function contraharmonic()
    {
        return $this->lehmerMean(2);
    }

    public function midrange()
    {
        $s = new self();
        $s->add(max($this->arr));
        $s->add(min($this->arr));

        return $s->mean;
    }

    public function midextreme()
    {
        return $this->midrange();
    }

    public function variance()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('Cannot compute variance on void collection');
        }

        if (is_null($this->float_variance)) {
            $this->float_variance = $this->centralMoment(2);
        }

        return $this->float_variance;
    }

    public function populationVariance()
    {
        return $this->variance();
    }

    public function covariance($data, $is_sample = false)
    {
        if (!is_array($data) && !($data instanceof Stats)) {
            throw new \InvalidArgumentException('Covariance must be compute using array or Stats instance');
        }

        if (is_array($data)) {
            $data = new self($data);
        }

        $a = ($this->sum() * $data->sum())/ count($this);

        $sum = 0;

        for ($i = 0; $i < count($this); $i++) {
            $sum += $this->get($i) * $data->get($i);
        }

        $cnt = $is_sample ? count($this) - 1 : count($this);

        return ($sum - $a)/ $cnt;
    }

    public function populationCovariance($data)
    {
        return $this->covariance($data);
    }

    public function sampleCovariance($data)
    {
        return $this->covariance($data, true);
    }

    public function standardDeviation()
    {
        return sqrt($this->variance());
    }

    public function stddev()
    {
        return $this->standardDeviation();
    }

    public function stdev()
    {
        return $this->standardDeviation();
    }

    public function sigma()
    {
        return $this->standardDeviation();
    }

    public function sampleVariance()
    {
        if (count($this) <= 1) {
            throw new \RuntimeException('Cannot compute sample variance, sample must have at least 2 elements');
        }

        if (is_null($this->float_sample_variance)) {
            $arr = array();

            for ($i = 0; $i < count($this); $i++) {
                $arr[] = pow($this->arr[$i] - $this->mean(), 2);
            }

            $this->float_sample_variance = array_sum($arr) / (count($this) - 1);
        }

        return $this->float_sample_variance;
    }

    public function s2()
    {
        return $this->sampleVariance();
    }

    public function centralMoment($k)
    {
        $arr = array();

        for ($i = 0; $i < count($this); $i++) {
            $arr[] = pow($this->arr[$i] - $this->mean(), $k);
        }

        return array_sum($arr) / count($this);
    }

    public function moment($k)
    {
        return $this->centralMoment($k);
    }

    public function kurtosis()
    {
        if (is_null($this->float_kurtosis)) {
            $this->float_kurtosis = ($this->moment(4) / pow($this->moment(2), 2)) - 3;
        }

        return $this->float_kurtosis;
    }

    public function isPlatykurtic()
    {
        return $this->kurtosis() < 0;
    }

    public function isLeptokurtic()
    {
        return $this->kurtosis() > 0;
    }

    public function isMesokurtic()
    {
        return $this->kurtosis() == 0;
    }

    public function quartile($n)
    {
        $arr = $this->arr;
        sort($arr, SORT_NUMERIC);

        if ($n == 1 || $n == 3) {
            return $arr[floor($n * count($this) / 4) - 1];
        } else {
            //odd
            if (count($this) & 1) {
                return $arr[floor(count($this) / 2)];
            }
            //even
            else {
                $s = new self();
                $s->add($arr[(count($this)/2) - 1]);
                $s->add($arr[count($this)/2]);

                return $s->mean;

            }
        }
    }

    public function median()
    {
        if (is_null($this->float_median)) {
            $this->float_median = $this->quartile(2);
        }

        return $this->float_median;
    }

    public function interquartileRange()
    {
        return $this->quartile(3) - $this->quartile(1);
    }

    public function iqr()
    {
        return $this->interquartileRange();
    }
    
    public function percentile($n)
    {
        if($n > 100 || $n < 0) {
            throw new \OutOfRangeException('Percent must be number between 0 and 100.');
        }

        $arr = $this->arr;
        sort($arr, SORT_NUMERIC);

        if($n == 100)
        {
            return end($arr);
        }

        if ($n != 50) {
            return $arr[floor($n * count($this) / 100)];
        } else {
            //odd
            if (count($this) & 1) {
                return $arr[floor(count($this) / 2)];
            }
            //even
            else {
                $s = new self();
                $s->add($arr[(count($this)/2) - 1]);
                $s->add($arr[count($this)/2]);

                return $s->mean;

            }
        }
    }


    public function skewness()
    {
        if (is_null($this->float_skew)) {
            $this->float_skew = $this->centralMoment(3) / pow($this->centralMoment(2), 3/2);
        }

        return $this->float_skew;
    }

    public function skew()
    {
        return $this->skewness();
    }

    public function isLeftSkewed()
    {
        return $this->skewness() < 0;
    }

    public function isRightSkewed()
    {
        return $this->skewness() > 0;
    }

    public function frequency()
    {
        if(is_null($this->arr_frequency)){
            $arr = array();

            foreach ($this->arr as $n) {
                $idx = "$n";

                if (isset($arr[$idx])) {
                    $arr[$idx]++;
                } else {
                    $arr[$idx] = 1;
                }
            }

            $this->arr_frequency = $arr;
        }

        return $this->arr_frequency;
    }

    public function cumulativeFrequency()
    {
        if(is_null($this->arr_cumulative_frequency)){
            $this->arr_cumulative_frequency = $this->frequency();
            $prev = 0;

            foreach($this->arr_cumulative_frequency as $k => $v){
                $prev += $v;
                $this->arr_cumulative_frequency[$k] = $prev;
            }

        }

        return $this->arr_cumulative_frequency;
    }


    /**
     * Relative frequency, like frequency, but divide each but total amount. 
     * 
     * @access public
     * @return array
     */
    public function f()
    {
        if(is_null($this->arr_f)){
            $arr = $this->frequency();
            array_walk(
                $arr, 
                function(&$v, $k, $n){
                    $v = $v / $n;
                }, 
                count($this)
            );

            $this->arr_f = $arr;
        }

        return $this->arr_f;
    }

    public function relativeFrequency()
    {
        return $this->f();
    }

    public function coefficientOfVariation()
    {
        return $this->standardDeviation() / $this->arithmeticMean();
    }

    public function cv()
    {
        return $this->coefficientOfVariation();
    }

    public function indexOfDispersion()
    {
        return $this->variance() / $this->arithmeticMean();
    }

    public function pearsonsR($data)
    {
        //TODO: complete and test it
        if (!is_array($data) && !($data instanceof Stats)) {
            throw new \InvalidArgumentException('PPMCC must be compute using array or Stats instance');
        }

        if (is_array($data)) {
            $data = new self($data);
        }

        return $this->covariance($data) / ($this->standardDeviation() * $data->stddev);
    }
}
