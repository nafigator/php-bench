<?php
/**
 * @todo <Test description here>
 * @file      CtypeVsRegex.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Fri Jun 21 18:28:00 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class CtypeDigitVsRegex
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class CtypeDigitVsRegex extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$string = '123412341243';

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			ctype_digit($string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('ctype_digit', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match('/^\d+$/', $string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('preg_match', Timer::get());
	}
}
