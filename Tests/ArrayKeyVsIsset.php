<?php
/**
 * Check what is faster array_key_exists or isset
 * @file    ArrayKeyVsIsset.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Tue Sep 24 17:34:28 2013
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ArrayKeyVsIsset
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class ArrayKeyVsIsset extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$array = ['prop' => 'value'];

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			array_key_exists('prop', $array);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('arr_key_ex', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			isset($array['prop']);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('isset', Timer::get());
	}
}
