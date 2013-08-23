<?php
/**
 * Check what is faster strtr or str_replace
 *
 * @file    StrtrVsStrReplace.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    2013-08-04 10:16
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class StrtrVsStrReplace
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class StrtrVsStrReplace extends TestApplication
{
	protected static $repeats = 1000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$namespace = 'Vendor\Package\Core';
		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$result = strtr($namespace, array('\\' => DIRECTORY_SEPARATOR));
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('strtr', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$result = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('str_replace', Timer::get());
	}
}
