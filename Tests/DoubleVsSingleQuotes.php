<?php
/**
 * Test performance impact of double and single quotes
 *
 * @file      DoubleVsSingleQuotes.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2018 Yancharuk Alexander
 * @date      Tue Aug 07 19:05:30 2018
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class DoubleVsSingleQuotes
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class DoubleVsSingleQuotes extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = 'qwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdf';
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Single', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$a = "qwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdfqwertyasdf";
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Double', Timer::get());
	}
}