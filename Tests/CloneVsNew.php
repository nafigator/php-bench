<?php
/**
 * Check what is faster new or clone
 *
 * @file      CloneVsNew.php
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
 * Class CloneVsNew
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class CloneVsNew extends TestApplication
{
	protected $repeats = 1000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$myobj1 = new \stdClass();
			$myobj2 = new \stdClass();
			$myobj3 = new \stdClass();
			$myobj4 = new \stdClass();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('New', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$myobj1 = new \stdClass();
			$myobj2 = clone $myobj1;
			$myobj3 = clone $myobj1;
			$myobj4 = clone $myobj1;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Clone', Timer::get());
	}
}
