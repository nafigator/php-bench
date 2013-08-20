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

use \Veles\Application\Application;
use \Veles\Tools\CliColor;

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

	/**
	 * Display results
	 */
	final public static function showResults()
	{
		$green = new CliColor;

		foreach (self::$results as $name => $value) {
			echo "$name\t{$green($value)} sec." . PHP_EOL;
		}
	}

	/**
	 * Add result for further displaying
	 *
	 * @param $name
	 * @param $value
	 */
	final public static function addResult($name, $value)
	{
		self::$results[$name] = $value;
	}
}
