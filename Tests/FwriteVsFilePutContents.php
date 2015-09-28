<?php
/**
 * @todo      <Test description here>
 * @file      FwriteVsFilePutContents.php
 *
 * PHP version 5.3.9+
 *
 * @author    Yancharuk Alexander <alex@itvault.info>
 * @date      Wed Aug 19 18:22:36 2015
 * @copyright The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class FwriteVsFilePutContents
 *
 * @author Yancharuk Alexander <alex@itvault.info>
 */
class FwriteVsFilePutContents extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$filename1 = strtolower(uniqid('file')) . '.txt';
		$filename2 = strtolower(uniqid('file')) . '.txt';
		$string = genStrShuffle(100);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$file = fopen($filename1, 'a+');
			flock($file, LOCK_EX);
			fwrite($file, $string);
			fclose($file);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('fwrite()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			file_put_contents($filename2, $string, FILE_APPEND | LOCK_EX);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('put_contents()', Timer::get());
		unlink($filename1);
		unlink($filename2);
	}
}

function genStrShuffle(
	$length  = 22,
	$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./'
) {
	return substr(str_shuffle(str_repeat($letters, 5)), 0, $length);
}
