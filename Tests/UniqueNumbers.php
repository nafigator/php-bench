<?php
/**
 * Find out a way to generate range of unique numbers
 *
 * @file      UniqueNumbers.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2019 Yancharuk Alexander
 * @date      Fri Jul 12 06:22:17 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class UniqueNumbers
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class UniqueNumbers extends TestApplication
{
    protected $repeats = 10;

	public function run()
	{
		$repeats = $this->getRepeats();
		$minValue = 20000;
		$maxValue = 2000000;
		$count = 100000;

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			generateShuffle($minValue, $maxValue, $count);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('generateShuffle', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			generateKeys($minValue, $maxValue, $count);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('generateKeys', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			generateValues($minValue, $maxValue, $count);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('generateValues', Timer::get());
	}
}

function generateShuffle($minValue, $maxValue, $count) {
	$numbers = range($minValue, $maxValue);
	shuffle($numbers);

	return array_slice($numbers, 0, $count);
}

function generateKeys($minValue, $maxValue, $count) {
	$result = [];

	while(count($result) < $count) {
		$result[rand($minValue, $maxValue)] = true;
	}

	return array_keys($result);
}

function generateValues($minValue, $maxValue, $count) {
	$result = [];
	$i = 0;

	while($i++ < $count) {
		while(in_array($num = mt_rand($minValue, $maxValue), $result));
		$result[] = $num;
	}

	return $result;
}
