<?php
/**
 * How much is performance difference for adding new array elements between
 * array and SplFixedArray
 *
 * @file      SplFixedArrayVsArrayAdd.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2017 Yancharuk Alexander
 * @date      Sat Mar 25 08:52:02 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use SplFixedArray;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class SplFixedArrayVsArrayAdd
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class SplFixedArrayVsArrayAdd extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$size    = 1000;

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$array = new SplFixedArray($size);
			Timer::start();
			for ($j = 0; $j < $size; ++$j) {
				$array[$j] = $i;
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('SplFixedArray', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$array = [];
			Timer::start();
			for ($j = 0; $j < $size; ++$j) {
				$array[] = $j;
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Array', Timer::get());
	}
}
