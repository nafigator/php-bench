<?php
/**
 * Check what is faster foreach or while
 * @file    ForeachVsWhile.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date	Втр Сен 10 17:03:20 2013
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class ForeachVsWhile
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class ForeachVsWhile extends TestApplication
{
	protected static $repeats = 1000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$j = 0; while (++$j <= $repeats) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('while', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			for ($j = 0; $j < $repeats; ++$j) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('for', Timer::get());
	}
}
