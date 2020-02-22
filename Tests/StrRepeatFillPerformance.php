<?php
/**
 * Check how much is performance drop for single byte parameter in str_repeat()
 *
 * @file      StrRepeatPerfCheck.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2020 Yancharuk Alexander
 * @date      Thu Nov 09 15:45:27 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class StrRepeatFillPerformance
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class StrRepeatFillPerformance extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			str_repeat('0000000000', 1639);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Multi-byte parameter', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			str_repeat('0', 16390);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Single-byte parameter', Timer::get());
	}
}
