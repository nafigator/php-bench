<?php
/**
 * What is faster for read Memcache or Memcached
 *
 * @file      MemcacheVsMemcachedRead.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @date      Птн Авг 23 11:05:53 2013
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use Veles\Cache\Adapters\MemcacheAdapter;
use Veles\Cache\Adapters\MemcachedAdapter;
use Veles\Cache\Cache;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
* Class MemcacheVsMemcachedRead
* @author  Yancharuk Alexander <alex at itvault dot info>
*/
class MemcacheVsMemcachedRead extends TestApplication
{
	protected $class_dependencies = ['Memcache', 'Memcached'];
	protected $ext_dependencies = ['Memcache', 'Memcached'];

	protected $repeats = 10000;

	public function run()
	{
		$this->initCache();

		$repeats = $this->getRepeats();
		$bar = new CliProgressBar($repeats);
		$key = md5('adfjkjkang');
		$data = range(0, 10);
		Cache::setAdapter(MemcacheAdapter::instance());
		Cache::set($key, $data);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			Cache::get($key);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Memcache', Timer::get());

		$bar = new CliProgressBar($repeats);

		Cache::setAdapter(MemcachedAdapter::instance());
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			Cache::get($key);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Memcached', Timer::get());
		Cache::clear();
	}

	private function initCache()
	{
		MemcachedAdapter::addCall('addServer', ['localhost', 11211]);
		MemcacheAdapter::addCall('addServer', ['localhost', 11211]);
	}
}
