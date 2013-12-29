<?php
/**
 * Check performance of comparing iso8601 dates algorithms
 * @file    CompareISO8601Dates.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Sun Dec 29 12:18:22 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class CompareISO8601Dates
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class CompareISO8601Dates extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$date1 = '2011-09-09';
		$date2 = '2011-09-08';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if (strtotime($date1) > strtotime($date2)) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('strtotime', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if (strcmp($date1, $date2) > 0) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('strcmp', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if (new \DateTime($date1) > new \DateTime($date2)) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('DateTime', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if ($date1 > $date2) {}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('string', Timer::get());
	}
}