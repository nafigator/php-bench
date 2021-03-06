<?php
/**
 * Check for fastest data filling algorithm
 *
 * @file      ReturnArrayVsObject.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Sat Sep 20 15:04:55 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class ReturnArrayVsObject
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class ReturnArrayVsObject extends TestApplication
{
    protected $repeats = 1000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$prop_name = 'property';
		$prop_count = 30;

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = static::returnArray($prop_count, $prop_name);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('return array', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$arr = static::createArray($prop_count, $prop_name);
			Timer::start();
			static::fillArray($arr, $prop_count, $prop_name);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('fill array', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			$obj = static::createObject($prop_count, $prop_name);
			Timer::start();
			static::fillObject($obj, $prop_count, $prop_name);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('fill object', Timer::get());
	}

	private static function fillObject($obj, $count, $prop_name)
	{
		$i = 0;
		while (++$i <= $count) {
			$obj->{$prop_name . $i} = uniqid();
		}
	}

	private static function createObject($count, $prop_name)
	{
		$obj = new \StdClass;

		$i = 0;
		while (++$i <= $count) {
			$obj->{$prop_name . $i} = '';
		}

		return $obj;
	}

	private static function createArray($count, $prop_name)
	{
		$arr = [];
		$i = 0;
		while (++$i <= $count) {
			$arr[$prop_name . $i] = '';
		}

		return $arr;
	}

	private static function fillArray(array &$arr, $count, $prop_name)
	{
		$i = 0;
		while (++$i <= $count) {
			$arr[$prop_name . $i] = uniqid();
		}
	}

	private static function returnArray($count, $prop_name)
	{
		$arr = [];
		$i = 0;
		while (++$i <= $count) {
			$arr[$prop_name . $i] = uniqid();
		}

		return $arr;
	}
}

