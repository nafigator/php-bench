<?php
/**
 * Check what is faster include or require
 * @file    IncludeVsRequire.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-04 10:16
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class IncludeVsRequire
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class IncludeVsRequire extends TestApplication
{
	protected static $repeats = 1;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		Timer::start();
		include 'ThisVsSelf.php';
		Timer::stop();
		$bar->update(1);

		self::addResult('include', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		Timer::start();
		require 'StrtrVsStrReplace.php';
		Timer::stop();
		$bar->update(1);

		self::addResult('require', Timer::get());
	}
}
