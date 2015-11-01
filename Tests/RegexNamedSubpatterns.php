<?php
/**
 * Find out performance impact of named regex subpatterns
 *
 * @file      RegexNamedSubpatterns.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2015 Yancharuk Alexander
 * @date      Sun Nov 01 13:47:51 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class RegexNamedSubpatterns
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class RegexNamedSubpatterns extends TestApplication
{
    protected $repeats = 10000;
	protected $result_format = "%-30s%-16s%-16s%-16s\n";

	public function run()
	{
		$repeats = $this->getRepeats();

		$uri = '/book/category/344/user/5443';
		$regex = '#^/book/category/(\d+)/user/(\d+)$#';
		$named_regex = '#^/book/category/(?P<category_id>\d+)/user/(?P<user_id>\d+)$#';
		$named_regex_alt1 = '#^/book/category/(?<category_id>\d+)/user/(?<user_id>\d+)$#';
		$named_regex_alt2 = '#^/book/category/(?\'category_id\'\d+)/user/(?\'user_id\'\d+)$#';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match($regex, $uri, $matches);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('regex', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match($named_regex, $uri, $matches);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('named regex (?P<name>)', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match($named_regex_alt1, $uri, $matches);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('named regex (?<name>)', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			preg_match($named_regex_alt2, $uri, $matches);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('named regex (?\'name\')', Timer::get());
	}
}
