<?php
/**
 * Check what is faster if or and
 * @file    IfVsAnd.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-24 14:01
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IfVsAnd
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class IfVsAnd extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$var = true;
		$tmp = false;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if ($var) {
				$tmp = $var;
			}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('if', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$var and $tmp = $var;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('and', Timer::get());
	}
}
