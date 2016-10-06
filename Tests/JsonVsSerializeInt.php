<?php
/**
 * What is the fastest way to convert array with integer values to string?
 *
 * @file      JsonVsSerializeInt.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2016 Yancharuk Alexander
 * @date      Tue Sep 29 16:05:45 2015
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class JsonVsSerializeInt
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class JsonVsSerializeInt extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$array   = create3DNumArray(3, 5);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			json_encode($array);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('json_encode()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			serialize($array);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('serialize()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			var_export($array, true);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('var_export()', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			print_r($array, true);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('print_r()', Timer::get());
	}
}

/**
 * Create x-dimensional numeric array
 *
 * @param int $max Max dimension
 * @param int $elements Max elements in each dimension
 * @param int $dim Current dimension
 *
 * @return array
 */
function create3DNumArray($max = 3, $elements = 100, $dim = 0)
{
	$i     = 0;
	$array = [];

	while (++$i <= $elements) {
		$key = $i;
		$tmp = $dim;

		$array[$key] = ($tmp !== $max)
			? create3DNumArray($max, $elements, ++$tmp)
			: rand();
	}

	return $array;
}
