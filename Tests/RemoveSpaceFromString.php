<?php
/**
 * Find fastest way to remove space from string
 *
 * @file      RemoveSpaceFromString.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2021 Yancharuk Alexander
 * @date      Thu Oct 06 12:25:56 2016
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class RemoveSpaceFromString
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class RemoveSpaceFromString extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$string  = 'this is string with spaces';

		Timer::reset();
		$result = '';
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = str_replace(' ', '', $string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('str_replace', Timer::get());

		Timer::reset();
		$result = '';
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = preg_replace('/\s+/', '', $string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('preg_replace', Timer::get());

		Timer::reset();
		$result = '';
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = strtok($string, ' ');
			while (false !== ($substr = strtok(' ')))
				$result .= $substr;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('strtok', Timer::get());

		Timer::reset();
		$result = '';
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$j = 0;
			$length = strlen($string);
			while ($j < $length) {
				if (' ' === $string[$j]) {
					++$j; continue;
				}

				$result .= $string[$j];
				++$j;
			}

			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('str as array', Timer::get());
	}
}
