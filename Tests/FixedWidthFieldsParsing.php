<?php
/**
 * Check if there is performance penalty for parsing string with fixed width fields by regex
 *
 * @file      FixedWidthFieldsParsing.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2017 Yancharuk Alexander
 * @date      Wed Nov 22 13:11:27 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class FixedWidthFieldsParsing
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class FixedWidthFieldsParsing extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
	    $string = '01/01/2001Description 25 symbols   8.23      0';
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$matches = unpack('A10date/A25desc/A10price/A*flag', $string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('unpack()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match('/(?<date>[\d\/]{10})(?<desc>.{25})(?<price>[\d\. ]{10})(?<flag>0|1)/', $string, $matches);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('preg_match()', Timer::get());
	}
}
