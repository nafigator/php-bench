<?php
/**
 * Find the fastest way to extract ID from multi-dimension array
 *
 * @file      ExtractIdFromMultiDimArr.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Сбт Фев 16 17:01:16 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ExtractIdFromMultiDimArr
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class ExtractIdFromMultiDimArr extends TestApplication
{
	protected $repeats = 1000;

	public function run()
	{
		$array = [
			['ID' => '2'],
			['ID' => '3']
		];

		$repeats = $this->getRepeats();
		$bar = new CliProgressBar($repeats);
		$result = [];
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = array_map(function($element) {
				return $element['ID'];
			}, $array);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('array_map', Timer::get());

		$bar = new CliProgressBar($repeats);

		$result = [];
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach ($array as $element) {
				$result[] = $element['ID'];
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('foreach', Timer::get());

		$bar = new CliProgressBar($repeats);

		$result = [];
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			foreach ($array as $element) {
				array_push($result, $element['ID']);
			}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('array_push', Timer::get());
	}
}
