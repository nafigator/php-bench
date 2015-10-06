<?php
/**
 * Find which increment syntax is fastest
 *
 * @file      FastestIncrement.php
 *
 * PHP version 5.3.9+
 *
 * @author    Yancharuk Alexander <alex@itvault.info>
 * @date      Fri Aug 14 18:02:18 2015
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class FastestIncrement
 *
 * @author Yancharuk Alexander <alex@itvault.info>
 */
class FastestIncrement extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		$x = 0;
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$x = $x + 1;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('$x = $x + 1', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		$x = 0;
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$x += 1;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('$x += 1', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		$x = 0;
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$x++;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('$x++', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		$x = 0;
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			++$x;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('++$x', Timer::get());
	}
}
