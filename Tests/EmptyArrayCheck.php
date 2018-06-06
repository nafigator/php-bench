<?php
/**
 * Find out fastest method for checking empty array
 *
 * @file      EmptyArrayCheck.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2018 Yancharuk Alexander
 * @date      Wed Jun 06 16:51:07 2018
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class EmptyArrayCheck
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class EmptyArrayCheck extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		$array = [0];

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			if (0 === count($array));
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('count', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
            if (empty($array));
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('empty', Timer::get());

        Timer::reset();
        $bar = new CliProgressBar($repeats);
        for ($i = 1; $i <= $repeats; ++$i) {
            Timer::start();
            if (!$array);
            Timer::stop();
            $bar->update($i);
        }

        $this->addResult('bool', Timer::get());
	}
}
