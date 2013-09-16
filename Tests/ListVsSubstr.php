<?php
/**
 * Check what is faster list+explode or substr+strpos
 * @file    ListVsSubstr.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Пнд Сен 16 15:31:22 2013
 * @copyright The BSD 3-Clause License
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ListVsSubstr
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class ListVsSubstr extends TestApplication
{
	protected static $repeats = 1000;

	final public static function run()
	{
		$repeats = self::getRepeats();
		$text = 'Administration\Controller\UserController::Save';

		$bar = new CliProgressBar($repeats);
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			list($module) = explode('::', $text);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('list+explode', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 0; $i <= $repeats; ++$i) {
			Timer::start();
			$module = substr($text, 0, strpos($text, '::'));
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('substr+strpos', Timer::get());
	}
}
