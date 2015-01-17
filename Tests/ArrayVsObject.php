<?php
/**
 * Check what is faster object properties initialization or array elements
 * @file    ArrayVsObject.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 * @date    Tue Sep 24 17:03:53 2013
 * @copyright The BSD 3-Clause License
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
	protected static $repeats = 1000;

	final public static function run()
	{
		$repeats = self::getRepeats();
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
		self::addResult('$object', Timer::get());
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

		self::addResult('$array', Timer::get());
	}
}
