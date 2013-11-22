<?php
/**
 * Check what is faster common for or foreach with range()
 * @file    ForVsRange.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-24 14:33
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class ForVsRange
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class ForVsRange extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$tmp = 100;
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			for ($j = 0; $j <= $tmp; ++$j) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('for', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach (range(0, $tmp) as $j) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('while & range', Timer::get());
	}
}
