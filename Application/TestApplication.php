<?php
/**
 * Test application class
 *
 * @file      TestApplication.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Сбт Фев 16 17:01:16 2013
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Application;

use Veles\Application\Application;
use Veles\Tools\CliColor;

/**
 * Class TestApplication
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class TestApplication extends Application
{
	/** @var array Results array */
	private static $results = [];
	/** @var array Class names of dependencies */
	protected static $class_dependencies = [];
	/** @var array Extension dependencies */
	protected static $ext_dependencies = [];
	/** @var int Test repeats */
	protected static $repeats = 10000;
	/** @var string Result output format */
	protected static $result_format = "%-16s%-16s%-16s%-16s\n";

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
			static::$result_format,
			'Test name', 'Repeats', 'Result', 'Performance'
		);

		foreach ($results as $name => $value) {
			list($percent, $color) = self::getPercentDiff(
				$results[$best], $value
			);

			$value = number_format($value, 6);
			$string->setColor($color);
			$string->setString($value);

			printf(
				self::getFixedFormat(),
				$name, self::getRepeats(), $string . ' sec', $percent . '%'
			);
		}
	}

	/**
	 * Printf format cant correctly align shell-colored string, so
	 * fix this by adding additional spaces
	 */
	private static function getFixedFormat()
	{
		$regexp = '/^%-\d+s%-\d+s%-(\d+)s%-\d+s\n$/';
		$match_result = preg_match($regexp, static::$result_format, $matches);
		$position = (1 === $match_result) ? $matches[1] + 11 : 16;

		return preg_replace('/^(%-\d+s%-\d+s%-)(\d+)(s%-\d+s\n)$/',
			'${1}' . $position . '$3', static::$result_format
		);
	}

	/**
	 * Calculate result percent difference
	 *
	 * @param int $best    float Best test result
	 * @param int $current float Result for comparison
	 *
	 * @return array [CliColor, string]
	 */
	private static function getPercentDiff($best, $current)
	{
		$diff    = $current - $best;
		$percent = $best / 100;
		$value   = $diff / $percent;
		$result  = new CliColor;

		if ($value > 0 and $value <= 10) {
			$color  = 'yellow';
			$value  = number_format($value, 2);
			$string = "-$value";
		} elseif ($value > 10) {
			$color  = 'red';
			$value  = number_format($value, 2);
			$string = "-$value";
		} else {
			$color  = 'green';
			$value  = number_format($value, 2);
			$string = "+$value";
		}

		$result->setColor($color);
		$result->setstring($string);

		return [$result, $color];
	}

	/**
	 * Add result for further displaying
	 *
	 * @param string $name
	 * @param float $value
	 */
	final public static function addResult($name, $value)
	{
		if ($value < 0.000001) $value = 0.000001;

		self::$results[$name] = $value;
	}
}
