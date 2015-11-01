<?php
/**
 * Check what is faster include or require
 *
 * @file      IncludeVsRequire.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      2013-08-04 10:16
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class IncludeVsRequire
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class IncludeVsRequire extends TestApplication
{
	protected $repeats = 1;

	public function run()
	{
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		Timer::start();
		include 'ThisVsSelf.php';
		Timer::stop();
		$bar->update(1);

		$this->addResult('include', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		Timer::start();
		require 'StrtrVsStrReplace.php';
		Timer::stop();
		$bar->update(1);

		$this->addResult('require', Timer::get());
	}
}
