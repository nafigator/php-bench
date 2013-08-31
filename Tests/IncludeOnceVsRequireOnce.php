<?php
/**
 * Check what is faster include or require
 * @file    IncludeOnceVsRequireOnce.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-31 16:43
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IncludeOnceVsRequireOnce
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class IncludeOnceVsRequireOnce extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		self::addResult('require', Timer::get());

		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			include_once 'ThisVsSelf.php';
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('include_once', Timer::get());

		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			require_once 'ThisVsSelf.php';
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('require_once', Timer::get());
	}
}
