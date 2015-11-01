<?php
/**
 * What is faster for save Memcache or Memcached
 *
 * @file      MemcacheVsMemcachedSave.php
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
* Class MemcacheVsMemcachedSave
* @author  Yancharuk Alexander <alex at itvault dot info>
*/
class MemcacheVsMemcachedSave extends TestApplication
{
	protected $class_dependencies = ['Memcache', 'Memcached'];
	protected $ext_dependencies = ['Memcache', 'Memcached'];

	protected $repeats = 10000;

	public function run()
	{
		$this->initCache();

		$repeats = $this->getRepeats();
		$bar = new CliProgressBar($repeats);
		$data = range(0, 99);
		Cache::setAdapter(MemcacheAdapter::instance());
		for ($i = 1; $i <= $repeats; ++$i) {
			$key = sha1(uniqid());
			Timer::start();
			Cache::set($key, $data);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Memcache', Timer::get());

		$bar = new CliProgressBar($repeats);
		Timer::reset();
		Cache::setAdapter(MemcachedAdapter::instance());
		for ($i = 1; $i <= $repeats; ++$i) {
			$key = sha1(uniqid());
			Timer::start();
			Cache::set($key, $data);
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
