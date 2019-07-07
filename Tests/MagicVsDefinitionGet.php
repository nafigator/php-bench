<?php
/**
 * Check for performance drop of magic methods
 *
 * @file      MagicVsDefinitionGet.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Sat Mar 18 14:11:52 2017
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class MagicVsDefinitionGet
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class MagicVsDefinitionGet extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$obj = new MagicGet;
			Timer::start();
			$obj->name;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Magic get', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$obj = new DefinedGet;
			Timer::start();
			$obj->getName();
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Defined get', Timer::get());
	}
}

class MagicGet
{
	private $data = ['name' => 'Nico'];

	public function __get($prop)
	{
		return isset($this->data[$prop]) ? $this->data[$prop] : null;
	}
}

class DefinedGet
{
	protected $name = 'Nico';

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
}
