<?php
/**
 * Check what is faster list+explode or substr+strpos
 *
 * @file      ListVsSubstr.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Пнд Сен 16 15:31:22 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ListVsSubstr
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class ListVsSubstr extends TestApplication
{
	protected $repeats = 1000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$text = 'Administration\Controller\UserController::Save';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			list($module) = explode('::', $text);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('list+explode', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$module = substr($text, 0, strpos($text, '::'));
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('substr+strpos', Timer::get());
	}
}
