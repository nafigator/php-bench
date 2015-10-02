<?php
/**
 * Check what is faster array_search or in_array
 *
 * @file      ArraySearchVsInArray.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Tue Dec 31 17:30:46 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ArraySearchVsInArray
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class ArraySearchVsInArray extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$haystack = [
			'apple',
			'banana',
			'cherry',
			'potato',
			'rutabaga'
		];
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
