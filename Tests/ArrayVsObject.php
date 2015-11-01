<?php
/**
 * Check what is faster object properties initialization or array elements
 *
 * @file      ArrayVsObject.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Tue Sep 24 17:03:53 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ArrayVsObject
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class ArrayVsObject extends TestApplication
{
	protected $repeats = 1000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$element_count = 50;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$object = new \stdClass();
			Timer::stop();
			for ($j = 0; $j <= $element_count; ++$j) {
				Timer::start();
				$object->{$j} = true;
				Timer::stop();
			}
			$bar->update($i);
		}

		unset($object);
		$this->addResult('$object', Timer::get());
		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$array = [];
			Timer::stop();
			for ($j = 0; $j <= $element_count; ++$j) {
				Timer::start();
				$array[$j] = true;
				Timer::stop();
			}
			$bar->update($i);
		}

		$this->addResult('$array', Timer::get());
	}
}
