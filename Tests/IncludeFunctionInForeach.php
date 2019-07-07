<?php
/**
 * Check performance drop from include/exclude expression in foreach cycle
 *
 * @file      IncludeFunctionInForeach.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Fri Dec 04 11:10:26 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class IncludeFunctionInForeach
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class IncludeFunctionInForeach extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$array = range(0,10);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$tmp = array_keys($array);
			foreach ($tmp as $value);
			Timer::stop();
			$bar->update($i);
		}

		unset($tmp, $value);
		$this->addResult('Outer function', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach (array_keys($array) as $value);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Inner function', Timer::get());
	}
}
