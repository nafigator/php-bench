<?php
/**
 * What is the fastest way to convert array with integer values to string?
 * @file      JsonVsSerializeInt.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2015 Yancharuk Alexander
 * @date      Tue Sep 29 16:05:45 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class JsonVsSerializeInt
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class JsonVsSerializeInt extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$array = range(100000, 100100);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			json_encode($array);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('json_encode', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			serialize($array);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('serialize', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			var_export($array, true);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('var_export', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			print_r($array, true);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('print_r', Timer::get());
	}
}
