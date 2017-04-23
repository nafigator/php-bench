<?php
/**
 * Check what is faster strtr or str_replace
 *
 * @file      StrtrVsStrReplace.php
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
 * Class StrtrVsStrReplace
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class StrtrVsStrReplace extends TestApplication
{
	protected $repeats = 1000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$namespace = 'Vendor\Package\Core';
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			strtr($namespace, ['\\' => DIRECTORY_SEPARATOR]);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('strtr', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('str_replace', Timer::get());
	}
}
