<?php
/**
 * Performance check for round to nearest thousand algorithms
 *
 * @file      RoundToNearestThousand.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Wed Jan 01 11:12:36 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class RoundToNearestThousand
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class RoundToNearestThousand extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$x = mt_rand(1, 999);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			round($x, -3);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('round', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			($x + 500) / 1000 * 1000;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('calculation', Timer::get());
	}
}
