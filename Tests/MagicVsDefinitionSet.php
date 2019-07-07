<?php
/**
 * Check for performance drop of magic methods
 *
 * @file      MagicVsDefinitionSet.php
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
 * Class MagicVsDefinitionSet
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class MagicVsDefinitionSet extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$value   = 'Chad';

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$obj = new MagicSet;
			Timer::start();
			$obj->name = $value;
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Magic set', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$obj = new DefinedSet;
			Timer::start();
			$obj->setName($value);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Defined set', Timer::get());
	}
}

class MagicSet
{
	private $data = ['name' => 'Nico'];

	public function __set($prop, $value)
	{
		if (isset($this->data[$prop])) {
			$this->data[$prop] = $value;
		}
	}
}

class DefinedSet
{
	protected $name = 'Nico';

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
}
