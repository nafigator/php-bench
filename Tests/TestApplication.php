<?php
/**
 * Test application class
 *
 * @file    TestApplication.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Сбт Фев 16 17:01:16 2013
 * @copyright The BSD 3-Clause License.
 */
namespace Tests;

use Veles\Application\Application;
use Veles\Tools\CliColor;

/**
 * Class TestApplication
 * @package Classes
 */
class TestApplication extends Application
{
	/**
	 * @var array Results array
	 */
	private static $results = array();
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
	 * Display results
	 */
	final public static function showResults()
	{
		$results = self::getResults();
		asort($results);
		$best = key($results);
		$string = new CliColor;

		printf(
			"%-12s\t%-12s\t%-12s\t%-12s\n",
			'Test name', 'Repeats', 'Result', 'Performance'
		);

		foreach ($results as $name => $value) {
			$color = ($name === $best || $results[$best] === $value)
				? 'green' : 'red';

			$percent = self::getPercentDiff($results[$best], $value);
			$value   = number_format($value, 6, ',', '');

			$string->setColor($color);
			$string->setString($value);
			printf(
				"%-12s\t%-12s\t%-12s\t%-12s\n",
				$name, self::getRepeats(), $string . ' sec', $percent . '%'
			);
		}
	}

	private static function getPercentDiff($best, $current)
	{
		$diff    = $current - $best;
		$percent = $best / 100;
		$value   = $diff / $percent;
		$result  = new CliColor;

		if ($value > 0) {
			$value = number_format($value, 2, ',', '');
			$result->setColor('red');
			$result->setstring("-$value");
		} else {
			$value = number_format($value, 2, ',', '');
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
