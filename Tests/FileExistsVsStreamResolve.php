<?php
/**
 * Find the fastest way to check file existence
 * @file    FileExistsVsStreamResolve.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Wed Jan 01 10:08:37 2014
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class FileExistsVsStreamResolve
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class FileExistsVsStreamResolve extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$temp_file = tempnam(sys_get_temp_dir(), 'pb-prefix');

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			file_exists($temp_file);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('file_exists', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			stream_resolve_include_path($temp_file);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('stream_resolve', Timer::get());

		unlink($temp_file);
	}
}
