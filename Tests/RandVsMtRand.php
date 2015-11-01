<?php
/**
 * Check what is faster rand() or mt_rand()
 *
 * @file      RandVsMtRand.php
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
 * Class RandVsMtRand
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class RandVsMtRand extends TestApplication
{
	protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			rand();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('rand', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			mt_rand();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('mt_rand', Timer::get());
	}
}
