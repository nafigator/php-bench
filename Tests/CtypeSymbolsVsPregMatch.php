<?php
/**
 * @todo <Test description here>
 * @file      CtypeSymbolsVsPregMatch.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Fri Jun 21 18:35:59 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class CtypeSymbolsVsPregMatch
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class CtypeSymbolsVsPregMatch extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
        $string = 'sdfsdfULKJlkj';

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
            ctype_upper($string) && ctype_lower($string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('ctype', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
            preg_match('/^[A-Za-z]+$/', $string);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('preg_match', Timer::get());
	}
}
