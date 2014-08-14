<?php
/**
 * Check for best recursive directory scan algorithm
 * @file    RecursiveDirectoryScan.php
 *
 * PHP version 5.3.9+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Thu Aug 14 11:12:31 2014
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class RecursiveDirectoryScan
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class RecursiveDirectoryScan extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$dir = self::createDir();

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			//TODO Place here your code
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('ResultName1', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			//TODO Place here your code
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('ResultName2', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			//TODO Place here your code
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('ResultName3', Timer::get());

		self::dirCleanup($dir);
	}

	/**
	 * Method for create multidimensional directory with files
	 *
	 * @param null|string $name  Directory name
	 * @param int         $max   Max dimension
	 * @param int         $files Max files in each dimension
	 * @param int         $dirs  Create directories quantity
	 * @param int         $dim   Current dimension
	 *
	 * @throws \Exception
	 * @return string Return root directory name
	 */
	final public static function createDir($name = null, $max = 3, $files = 30, $dirs = 3, $dim = 0)
	{
		if ($max < $dim) return $name;

		if (null === $name && 0 === $dim) {
			$dir = __DIR__ . uniqid('/dir-');
			mkdir($dir, 0777, true);
			self::createDir($dir, $max, $files, $dirs, $dim + 1);

			return $dir;
		}

		$i = $dirs;
		while (--$i >= 0) {
			$dir = $name . uniqid('/dir-');
			mkdir($dir, 0777, true);
			self::createDir($dir, $max, $files, $dirs, $dim + 1);
		}

		// Создаём временные файлы
		while (--$files >= 0) {
			$file_name = $name . uniqid('/file-') . '.tmp';
			fopen($file_name, 'w');
		}

		return $name;
	}

	/**
	 * Method for cleanup multidimensional directory with files
	 *
	 * @param string $dir Directory name for cleanup
	 */
	final public static function dirCleanup($dir)
	{
		foreach (scandir($dir) as $name) {
			if ('..' === $name or '.' === $name) continue;

			$path = $dir . '/' . $name;

			if (is_dir($path) and is_writable($path)) {
				self::dirCleanup($path);
				continue;
			}

			unlink($path);
		}

		rmdir($dir);
	}
}
