<?php
/**
* What is faster for read Memcache or Memcached
*
* @file    MemcacheVsMemcachedRead.php
*
* PHP version 5.3.9+
*
* @author  Yancharuk Alexander <alex@itvault.info>
* @date    Птн Авг 23 11:05:53 2013
* @copyright The BSD 3-Clause License.
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
* @author  Yancharuk Alexander <alex@itvault.info>
*/
class MemcacheVsMemcachedRead extends TestApplication
{
	protected static $class_dependencies = array('Memcache', 'Memcached');
	protected static $ext_dependencies = array('Memcache', 'Memcached');

	protected static $repeats = 10000;

	final public static function run()
	{
		self::initCache();

		$repeats = self::getRepeats();
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

		self::addResult('Memcache', Timer::get());

		$bar = new CliProgressBar($repeats);

		Cache::setAdapter(MemcachedAdapter::instance());
		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			Cache::get($key);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Memcached', Timer::get());
		Cache::clear();
	}

	private static function initCache()
	{
		MemcachedAdapter::addCall('addServer', array('localhost', 11211));
		MemcacheAdapter::addCall('addServer', array('localhost', 11211));
	}
}
