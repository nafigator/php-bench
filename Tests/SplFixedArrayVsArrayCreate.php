<?php
/**
 * How much is performance difference for create array and new SplFixedArray
 *
 * @file      SplFixedArrayVsArrayCreate.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2021 Yancharuk Alexander
 * @date      Sat Mar 25 09:09:51 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use SplFixedArray;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class SplFixedArrayVsArrayCreate
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class SplFixedArrayVsArrayCreate extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$size    = 1000;

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$array = new SplFixedArray($size);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('SplFixedArray', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$array = [];
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Array', Timer::get());
	}
}
