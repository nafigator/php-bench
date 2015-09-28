<?php
/**
 * What is the fastest way to make hash over array?
 *
 * Test for json_encode(), var_export() and serialize() performance
 *
 * @file      JsonVsSerialize.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2015 Yancharuk Alexander
 * @date      Mon Sep 28 16:56:21 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class JsonVsSerialize
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class JsonVsSerialize extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$array   = range(1, 100);

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
	}
}
