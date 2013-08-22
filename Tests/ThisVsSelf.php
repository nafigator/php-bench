<?php
/**
 * Check what is there overhead when used static call on dynamic methods
 *
 * @file    ThisVsSelf.php
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
 * Class ThisVsSelf
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class ThisVsSelf extends TestApplication
{
	final public static function run()
	{
		$repeats = 100000;
		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$var = new Test;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('This', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$var = new TestSelf;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Self', Timer::get());
	}
}

class Test
{
	public function __construct()
	{
		$this->testMethod();
	}

	public function testMethod()
	{
		return true;
	}
}

class TestSelf
{
	public function __construct()
	{
		self::testMethod();
	}

	public function testMethod()
	{
		return true;
	}
}
