<?php
/**
 * Check what is faster - crc32 or md5 hashing algorithms
 * @file    Crc32VsMd5.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Сбт Фев 16 17:01:16 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class Crc32VsMd5
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class Crc32VsMd5 extends TestApplication
{
	const TEST = 'simple value';
	protected static $repeats = 1000;

	final public static function run()
	{
        $string = 'some random data';

		$repeats = self::getRepeats();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			dechex(crc32($string));
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('dechex(crc32())', Timer::get());

		$bar = new CliProgressBar($repeats);
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			hash('crc32b', $string);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('hash(\'crc32b\')', Timer::get());

        $bar = new CliProgressBar($repeats);
        Timer::reset();
        for ($i = 1; $i <= $repeats; ++$i) {
            Timer::start();
            md5($string);
            Timer::stop();
            $bar->update($i);
        }

        self::addResult('md5()', Timer::get());
	}
}
