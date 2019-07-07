<?php
/**
 * Check performance drop for loading classes from phar-archive
 *
 * @file      PharAutoLoad.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Sun Apr 23 09:27:03 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class PharAutoLoad
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class PharAutoLoad extends TestApplication
{
    protected $repeats = 1;
    protected $ext_dependencies = ['Phar'];

	public function run()
	{
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			new \NotPhar\ClassOne;
			new \NotPhar\ClassTwo;
			new \NotPhar\ClassThree;
			new \NotPhar\ClassFour;
			new \NotPhar\ClassFive;
			new \NotPhar\ClassSix;
			new \NotPhar\ClassSeven;
			new \NotPhar\ClassEight;
			new \NotPhar\ClassNine;
			new \NotPhar\ClassTen;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('not phar', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		// unregister PSR-autoloader for clean performance results
		spl_autoload_unregister(['Veles\AutoLoader', 'load']);

		require __DIR__ . '/../lib/archive.phar';

		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			new \Phar\ClassOne;
			new \Phar\ClassTwo;
			new \Phar\ClassThree;
			new \Phar\ClassFour;
			new \Phar\ClassFive;
			new \Phar\ClassSix;
			new \Phar\ClassSeven;
			new \Phar\ClassEight;
			new \Phar\ClassNine;
			new \Phar\ClassTen;
			Timer::stop();
			$bar->update($i);
		}

		spl_autoload_register(['Veles\AutoLoader', 'load']);
		$this->addResult('phar', Timer::get());
	}
}
