<?php
/**
 * Which method is faster for getting first element: array key or current()?
 *
 * @file      GetFirstArrayElement.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Sat Mar 18 14:30:34 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class GetFirstArrayElement
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class GetFirstArrayElement extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$array = ['value-1', 'value-2'];

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$array[0];
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Key', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			current($array);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Current()', Timer::get());
	}
}
