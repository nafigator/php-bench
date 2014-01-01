<?php
/**
 * Performance test of is_a() and instancheof
 *
 * @file    IsAVsInstanceOf.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Wed Jan 01 11:53:52 2014
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class IsAVsInstanceOf
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class IsAVsInstanceOf extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$object = new TestClass;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			is_a($object, 'TestClass');
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('is_a()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$object instanceof TestClass;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('instanceof', Timer::get());
	}
}

class TestClass
{
	private $private;
}
