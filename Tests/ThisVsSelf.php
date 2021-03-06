<?php
/**
 * Check what is there overhead when used static call on dynamic methods
 *
 * @file      ThisVsSelf.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      2013-08-04 10:16
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ThisVsSelf
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class ThisVsSelf extends TestApplication
{
	public function run()
	{
		$repeats = 100000;
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$var = new Test;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('This', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$var = new TestSelf;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Self', Timer::get());
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
