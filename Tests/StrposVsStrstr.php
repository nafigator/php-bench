<?php
/**
 * Check what is faster strstr or strpos
 *
 * @file      StrposVsStrstr.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Wed Oct  2 16:42:55 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class StrposVsStrstr
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class StrposVsStrstr extends TestApplication
{
	protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$string = 'This is test string';
		$needle = 'is test';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			strpos($string, $needle);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('strpos', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			strstr($string, $needle);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('strstr', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			substr($string, 0, strlen($needle));
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('substr+strlen', Timer::get());
	}
}
