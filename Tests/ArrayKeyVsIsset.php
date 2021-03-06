<?php
/**
 * Check what is faster array_key_exists or isset
 *
 * @file      ArrayKeyVsIsset.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Tue Sep 24 17:34:28 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class ArrayKeyVsIsset
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class ArrayKeyVsIsset extends TestApplication
{
	protected $repeats = 10000;
	protected $result_format = "%-20s%-16s%-16s%-16s\n";

	public function run()
	{
		$repeats = $this->getRepeats();
		$array = ['prop' => 'value'];

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			array_key_exists('prop', $array);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('array_key_exists', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			isset($array['prop']);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('isset', Timer::get());
	}
}
