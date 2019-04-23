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
	private $results = [];
	/** @var array Class names of dependencies */
	protected $class_dependencies = [];
	/** @var array Extension dependencies */
	protected $ext_dependencies = [];
	/** @var int Test repeats */
	protected $repeats = 10000;
	/** @var string Result output format */
	protected $result_format = "%-16s%-16s%-16s%-16s\n";

	/**
	 * @param int $repeats
	 */
	public function setRepeats($repeats)
	{
		$this->repeats = $repeats;
	}

	/**
	 * @return int
	 */
	public function getRepeats()
	{
		return $this->repeats;
	}

	/**
	 * @param array $results
	 */
	public function setResults($results)
	{
		$this->results = $results;
	}

	/**
	 * @return array
	 */
	public function getResults()
	{
		return $this->results;
	}

	/**
	 * Test dependencies
	 */
	public function testDependencies()
	{
		$errors = '';
		foreach ($this->class_dependencies as $class_name) {
			if (class_exists($class_name)) continue;
			$errors .= sprintf(
				"%-12s%-20s%-10s\n", 'Class ', $class_name, ' not found!'
			);
		}

		foreach ($this->ext_dependencies as $ext_name) {
			if (extension_loaded($ext_name)) continue;
			$errors .= sprintf(
				"%-12s%-20s%-10s\n", 'Extension ', $ext_name, ' not loaded!'
			);
		}

		if ('' !== $errors) {
			throw new DependencyException($errors);
		}
	}

	/**
	 * Display results
	 */
	public function showResults()
	{
		$results = $this->getResults();
		asort($results);
		$best = key($results);
		$string = new CliColor;

		printf(
			$this->result_format,
			'Test name', 'Repeats', 'Result', 'Performance'
		);

		foreach ($results as $name => $value) {
			list($percent, $color) = $this->getPercentDiff(
				$results[$best], $value
			);

			$value = number_format($value, 6);
			$string->setColor($color);
			$string->setString($value);

			printf(
				$this->getFixedFormat(),
				$name, $this->getRepeats(), $string . ' sec', $percent . '%'
			);
		}
	}

	/**
	 * Printf format cant correctly align shell-colored string, so
	 * fix this by adding additional spaces
	 */
	private function getFixedFormat()
	{
		$regexp = '/^%-\d+s%-\d+s%-(\d+)s%-\d+s\n$/';
		$match_result = preg_match($regexp, $this->result_format, $matches);
		$position = (1 === $match_result) ? $matches[1] + 11 : 16;

		return preg_replace('/^(%-\d+s%-\d+s%-)(\d+)(s%-\d+s\n)$/',
			'${1}' . $position . '$3', $this->result_format
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
	private function getPercentDiff($best, $current)
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
	public function addResult($name, $value)
	{
		if ($value < 0.000001) $value = 0.000001;

		$this->results[$name] = $value;
	}
}
