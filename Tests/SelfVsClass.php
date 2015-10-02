<?php
/**
 * Check what is faster - self::CONST or class::CONST
 *
 * @file      SelfVsClass.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Сбт Фев 16 17:01:16 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class SelfVsClass
 * @author  Yancharuk Alexander <alex at itvault dot info>
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
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = self::TEST;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('self::TEST', Timer::get());

		$bar = new CliProgressBar($repeats);

		$a = 0;
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = SelfVsClass::TEST;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('class::TEST', Timer::get());
	}
}
