<?php
/**
 * Check most efficient algorithm for checking value in multidimensional associative array
 * @file    RecursiveArrayIteratorPerformance.php
 *
 * PHP version 5.4+
 *
 * @author  Alexander Yancharuk <alex@itvault.info>
 * @date    Thu Dec 12 10:06:42 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Tests;

use Application\TestApplication;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class RecursiveArrayIteratorPerformance
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class RecursiveArrayIteratorPerformance extends TestApplication
{
    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$last_leave = '';
		$array = create3DAssocArray(3, 10);

		$leaves = new RecursiveIteratorIterator(
			new RecursiveArrayIterator($array),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($leaves as $leave) $last_leave = $leave;

		$it = new RecursiveIteratorIterator(
			new RecursiveArrayIterator($array)
		);

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			checkIterator($last_leave, $it);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Iterator', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			checkKeyIsInArray($last_leave, $array);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Custom func', Timer::get());
	}
}

/**
 * Create x-dimensional associative array
 *
 * @param int $max Max dimension
 * @param int $elements Max elements in each dimension
 * @param int $dim Current dimension
 * @return array
 */
function create3DAssocArray($max = 3, $elements = 100, $dim = 0)
{
	$i = 0;
	$array = array();

	while (++$i <= $elements) {
		$key = uniqid('key::');
		$tmp = $dim;

		$array[$key] = ($tmp !== $max)
			? create3DAssocArray($max, $elements, ++$tmp)
			: uniqid('value::');
	}

	return $array;
}

/**
 * Function for search nested key using RecursiveIteratorIterator
 *
 * @param string $needle String for search in keys
 * @param \RecursiveIteratorIterator $iterator
 * @return bool
 * @see http://stackoverflow.com/questions/20530359/php-find-key-in-nested-associtive-array
 */
function checkIterator ($needle, RecursiveIteratorIterator $iterator)
{
	foreach ($iterator as $value) {
		if ($needle === $value) return true;
	}
	return false;
}

/**
 * Function proposed for search nested key
 *
 * @param $dataItemName
 * @param $array
 * @return bool
 * @see http://stackoverflow.com/questions/20530359/php-find-key-in-nested-associtive-array
 */
function checkKeyIsInArray($dataItemName, $array)
{
	foreach ($array as $key => $value) {
		if ((string) $key == $dataItemName)
			return true;
		if (is_array($value) && checkKeyIsInArray($dataItemName, $value))
			return true;
	}

	return false;
}
