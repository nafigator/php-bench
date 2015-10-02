<?php
/**
 * Check what is faster if or and
 *
 * @file      IfVsAnd.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      2013-08-24 14:01
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IfVsAnd
 * @author  Yancharuk Alexander <alex at itvault dot info>
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
