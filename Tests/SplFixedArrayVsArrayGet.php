<?php
/**
 * How much is performance difference for getting elements form array
 * and SplFixedArray
 *
 * @file      SplFixedArrayVsArrayGet.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2017 Yancharuk Alexander
 * @date      Sat Mar 25 09:12:31 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use SplFixedArray;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class SplFixedArrayVsArrayGet
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class SplFixedArrayVsArrayGet extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$size    = 1000;
		$array   = range(1, $size);
		$fixed   = SplFixedArray::fromArray($array);

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$idx = rand(0, $size - 1);
			Timer::start();
			$array[$idx];
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Array', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$idx = rand(0, $size - 1);
			Timer::start();
			$fixed[$idx];
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('SplFixedArray', Timer::get());
	}
}
