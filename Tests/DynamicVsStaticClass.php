<?php
/**
 * Calculate performance drop from dynamic methods call
 *
 * @file      DynamicVsStaticClass.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2021 Yancharuk Alexander
 * @date      Tue Nov 10 13:17:44 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class DynamicVsStaticClass
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class DynamicVsStaticClass extends TestApplication
{
    protected $repeats = 10000;
	protected $result_format = "%-25s%-16s%-16s%-16s\n";

	public function run()
	{
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$class = new DynamicClass;
			$class->testMethod();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Dynamic calls', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			(new DynamicClass)->testMethod();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Simple dynamic syntax', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			StaticClass::testMethod();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Static calls', Timer::get());
	}
}

class DynamicClass
{
	public function testMethod() {}
}

class StaticClass
{
	public static function testMethod() {}
}
