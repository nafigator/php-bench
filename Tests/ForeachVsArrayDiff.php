<?php
/**
 * What is faster unset in foreach loop or array_diff_key($a, array_flip($b))?
 *
 * @file      ForeachVsArrayDiff.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Tue Dec 10 10:42:28 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ForeachVsArrayDiff
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class ForeachVsArrayDiff extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$max = 1000;
		$min = 0;

		$a1 = $a2 = range($min, $max);
		$b1 = $b2 = [];
		$i = $min - 1;
		while(++$i <= $max) {
			$b1[$i] = $b2[$i] = mt_rand($min, $max);
		}

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach ($b1 as $val) {
				unset($a1[$val]);
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('foreach', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a2 = array_diff_key($a2, array_flip($b2));
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('array_diff_key', Timer::get());
	}
}
