<?php
/**
 * Check performance file_put_contents function and its analog fwrite
 *
 * @file      FwriteVsFilePutContents.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Wed Aug 19 18:22:36 2015
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class FwriteVsFilePutContents
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class FwriteVsFilePutContents extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
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

		$this->addResult('fwrite()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			file_put_contents($filename2, $string, FILE_APPEND | LOCK_EX);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('put_contents()', Timer::get());
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
