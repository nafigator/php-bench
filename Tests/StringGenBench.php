<?php
/**
 * Check performance of two functions that make strings
 * @file    StringGenBench.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Втр Сен 10 16:03:47 2013
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class StringGenBench
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class StringGenBench extends TestApplication
{
	protected static $repeats = 1000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			genStrSuffle();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('genStr', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			genStrArr();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('genStrOpt', Timer::get());
	}
}

function genStrSuffle(
	$length  = 22,
	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./'
) {
	return substr(str_shuffle(str_repeat($letters, 5)), 0, $length);
}

function genStrArr(
	$length  = 22,
	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./'
) {
	$str_len = strlen($letters) - 1;
	$result = '';
	$i = 0;
	while (++$i <= $length) { $result .= $letters[mt_rand(0, $str_len)]; }

	return $result;
}
