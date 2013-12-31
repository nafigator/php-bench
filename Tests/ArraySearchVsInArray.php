<?php
/**
 * Check what is faster array_search or in_array
 * @file    ArraySearchVsInArray.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Tue Dec 31 17:30:46 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class ArraySearchVsInArray
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class ArraySearchVsInArray extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$haystack = array(
			'apple',
			'banana',
			'cherry',
			'potato',
			'rutabaga'
		);
		$needle = 'rutabaga';

		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			array_search($needle, $haystack);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('array_search', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			in_array($needle, $haystack);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('in_array', Timer::get());
	}
}
