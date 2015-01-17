<?php
/**
 * Check what is faster strstr or strpos
 *
 * @file    StrposVsStrstr.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 * @date    Wed Oct  2 16:42:55 2013
 * @copyright The BSD 3-Clause License
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
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$string = 'This is test string';
		$needle = 'is test';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = strpos($string, $needle);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('strpos', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = strstr($string, $needle);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('strstr', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = substr($string, 0, strlen($needle));
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('substr+strlen', Timer::get());
	}
}
