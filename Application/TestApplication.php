<?php
/**
 * Test application class
 *
 * @file    TestApplication.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Сбт Фев 16 17:01:16 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Application;

use Veles\Application\Application;
use Veles\Tools\CliColor;

/**
 * Class TestApplication
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 */
class TestApplication extends Application
{
	/** @var array Results array */
	private static $results = array();
	/** @var array Class names of dependencies */
	protected static $class_dependencies = array();
	/** @var array Extension dependencies */
	protected static $ext_dependencies = array();
	/** @var int Test repeats */
	protected static $repeats = 10000;

	/**
	 * @param int $repeats
	 */
	final public static function setRepeats($repeats)
	{
		static::$repeats = $repeats;
	}

	/**
	 * @return int
	 */
	final public static function getRepeats()
	{
		return static::$repeats;
	}

	/**
	 * @param array $results
	 */
	final public static function setResults($results)
	{
		self::$results = $results;
	}

	/**
	 * @return array
	 */
	final public static function getResults()
	{
		return self::$results;
	}

	/**
	 * Test dependencies
	 */
	final public static function testDependencies()
	{
		$errors = '';
		foreach (static::$class_dependencies as $class_name) {
			if (class_exists($class_name)) continue;
			$errors .= sprintf(
				"%-12s%-20s%-10s\n", 'Class', $class_name, 'not found!'
			);
		}

		foreach (static::$ext_dependencies as $ext_name) {
			if (extension_loaded($ext_name)) continue;
			$errors .= sprintf(
				"%-12s%-20s%-10s\n", 'Extension', $ext_name, 'not loaded!'
			);
		}

		if ('' === $errors) return;

		throw new DependencyException($errors);
	}

	/**
	 * Display results
	 */
	final public static function showResults()
	{
		$results = self::getResults();
		asort($results);
		$best = key($results);
		$string = new CliColor;

		printf(
			"%-16s%-16s%-16s%-16s\n",
			'Test name', 'Repeats', 'Result', 'Performance'
		);

		foreach ($results as $name => $value) {
			$color = ($name === $best || $results[$best] === $value)
				? 'green' : 'red';

			$percent = self::getPercentDiff($results[$best], $value);
			$value   = number_format($value, 6);

			$string->setColor($color);
			$string->setString($value);
			printf(
				"%-16s%-16s%-27s%-16s\n",
				$name, self::getRepeats(), $string . ' sec', $percent . '%'
			);
		}
	}

	/**
	 * Calculate result percent difference
	 *
	 * @param $best float Best test result
	 * @param $current float Result for comparison
	 * @return CliColor
	 */
	private static function getPercentDiff($best, $current)
	{
		$diff    = $current - $best;
		$percent = $best / 100;
		$value   = $diff / $percent;
		$result  = new CliColor;

		if ($value > 0) {
			$value = number_format($value, 2);
			$result->setColor('red');
			$result->setstring("-$value");
		} else {
			$value = number_format($value, 2);
			$result->setColor('green');
			$result->setstring("+$value");
		}

		return $result;
	}

	/**
	 * Add result for further displaying
	 *
	 * @param string $name
	 * @param float $value
	 */
	final public static function addResult($name, $value)
	{
		self::$results[$name] = $value;
	}
}
