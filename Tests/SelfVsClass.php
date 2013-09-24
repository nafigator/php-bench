<?php
/**
 * Check what is faster - self::CONST or class::CONST
 * @file    SelfVsClass.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Сбт Фев 16 17:01:16 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class SelfVsClass
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class SelfVsClass extends TestApplication
{
	const TEST = 'simple value';
	protected static $repeats = 10000;

	final public static function run()
	{
		$a = 0;
		$repeats = self::getRepeats();
		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$a = self::TEST;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('self::TEST', Timer::get());

		$bar = new CliProgressBar($repeats);

		$a = 0;
		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$a = SelfVsClass::TEST;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('class::TEST', Timer::get());
	}
}
