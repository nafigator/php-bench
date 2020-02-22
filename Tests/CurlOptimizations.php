<?php
/**
 * Is there performance boost from CURLOPT_ENCODING and CURLOPT_IPRESOLVE curl options
 *
 * @see https://stackoverflow.com/questions/19467449/how-to-speed-up-curl-in-php/19468010
 *
 * @file      CurlOptimizations.php
 *
 * PHP version 5.6+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2020 Yancharuk Alexander
 * @date      Tue Apr 23 10:34:20 2019
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class CurlOptimizations
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class CurlOptimizations extends TestApplication
{
	protected $ext_dependencies = ['curl'];

	protected $repeats = 50;

	public function run()
	{
		$repeats = $this->getRepeats();
		$curlHandler1 = curl_init('https://ya.ru');

		curl_setopt_array($curlHandler1, [
			CURLOPT_RETURNTRANSFER => true,
		]);

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			curl_exec($curlHandler1);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('default', Timer::get());
		curl_close($curlHandler1);

		$curlHandler2 = curl_init('https://ya.ru');
		curl_setopt_array($curlHandler2, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
		]);

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			curl_exec($curlHandler2);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('optimized', Timer::get());
		curl_close($curlHandler2);

		$curlHandler3 = curl_init('https://ya.ru');
		curl_setopt_array($curlHandler3, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => 'gzip',
			CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		]);

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			curl_exec($curlHandler3);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('opt + no SSL', Timer::get());
		curl_close($curlHandler3);
	}
}
