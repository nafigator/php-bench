<?php
/**
 * Check what is faster rand() or mt_rand()
 * @file    RandVsMtRand.php
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
 * Class RandVsMtRand
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class RandVsMtRand extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			rand();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('rand', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			mt_rand();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('mt_rand', Timer::get());
	}
}
