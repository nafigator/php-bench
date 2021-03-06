<?php
/**
 * Check what is faster include or require
 *
 * @file      IncludeOnceVsRequireOnce.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      2013-08-31 16:43
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IncludeOnceVsRequireOnce
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class IncludeOnceVsRequireOnce extends TestApplication
{
	protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			include_once 'ThisVsSelf.php';
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('include_once', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			require_once 'ThisVsSelf.php';
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('require_once', Timer::get());
	}
}
