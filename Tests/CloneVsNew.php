<?php
/**
 * Check what is faster new or clone
 * @file    CloneVsNew.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-04 10:16
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use \Veles\Tools\Timer;
use \Veles\Tools\CliProgressBar;

/**
 * Class CloneVsNew
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class CloneVsNew extends TestApplication
{
	final public static function run()
	{
		$repeats = 100000;
		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$myobj1=new \stdClass();
			$myobj2=new \stdClass();
			$myobj3=new \stdClass();
			$myobj4=new \stdClass();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('New', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$myobj1 = new \stdClass();
			$myobj2 = clone $myobj1;
			$myobj3 = clone $myobj1;
			$myobj4 = clone $myobj1;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Clone', Timer::get());
	}
}
