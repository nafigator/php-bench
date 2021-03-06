<?php
/**
 * Performance test of is_a() and instanceof
 *
 * @file      IsAVsInstanceOf.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Wed Jan 01 11:53:52 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IsAVsInstanceOf
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class IsAVsInstanceOf extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$object = new TestClass;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			is_a($object, 'TestClass');
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('is_a()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$object instanceof TestClass;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('instanceof', Timer::get());
	}
}

class TestClass
{
	private $private;
}
