<?php
/**
 * Check which way for receiving class name is faster
 *
 * @file      GetClassName.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2017 Yancharuk Alexander
 * @date      Thu Dec 01 18:42:13 2016
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class GetClassName
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class GetClassName extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			get_class($this);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('get_class', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			self::class;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('self::class', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			GetClassName::class;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Class::class', Timer::get());
	}
}
