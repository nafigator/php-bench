<?php
/**
 * Performance check for round to nearest thousand algorithms
 *
 * @file    RoundToNearestThousand.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Wed Jan 01 11:12:36 2014
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class RoundToNearestThousand
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class RoundToNearestThousand extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$x = mt_rand(1, 999);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			round($x, -3);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('round', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			($x + 500) / 1000 * 1000;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('calculation', Timer::get());
	}
}
