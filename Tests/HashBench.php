<?php
/**
 * Benchmark PHP hash algorithms
 *
 * @file      HashBench.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Sat Sep 20 03:37:20 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class HashBench
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class HashBench extends TestApplication
{
    protected $repeats = 10000;

	public function run()
	{
		$repeats = $this->getRepeats();
		$string = uniqid();
		$hashes = hash_algos();

		foreach ($hashes as $hash_name) {
			$bar = new CliProgressBar($repeats);
			for ($i = 1; $i <= $repeats; ++$i) {
				Timer::start();
				hash($hash_name, $string);
				Timer::stop();
				$bar->update($i);
			}

			$this->addResult($hash_name, Timer::get());

			Timer::reset();
		}
	}
}
