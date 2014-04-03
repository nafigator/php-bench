<?php
/**
 * What is faster typecasting or function intval()
 * @file    IntvalVsTypeCast.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Thu Apr 03 14:39:45 2014
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class IntvalVsTypeCast
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class IntvalVsTypeCast extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = (int) 5.55;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('(int)', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = intval(5.55);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('intval()', Timer::get());
	}
}
