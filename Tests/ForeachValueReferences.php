<?php
/**
 * Check is there performance burst when references used in foreach
 *
 * @file      ForeachValueReferences.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2015 Yancharuk Alexander
 * @date      Mon Jun 20 18:40:39 2016
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class ForeachValueReferences
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
	class ForeachValueReferences extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$numeric_array = range(0, 1000);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach ($numeric_array as $key => &$value) {}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('references', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach ($numeric_array as $key => $value) {}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('variables', Timer::get());
	}
}
