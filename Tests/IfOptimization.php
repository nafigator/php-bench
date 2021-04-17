<?php
/**
 * @todo <Test description here>
 * @file      IfOptimization.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2021 Yancharuk Alexander
 * @date      Thu Jun 02 10:38:50 2016
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class IfOptimization
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class IfOptimization extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$value = rand(0, 255);
		$count = 0;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if ($value > 128) {
				$count++;
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('if', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$count += $value >> 7;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('bit operation', Timer::get());
	}
}
