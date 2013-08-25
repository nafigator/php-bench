<?php
/**
* What is faster for save Memcache or Memcached
*
* @file    MemcacheVsMemcachedSave.php
*
* PHP version 5.3.9+
*
* @author  Yancharuk Alexander <alex@itvault.info>
* @date    Птн Авг 23 11:05:53 2013
* @copyright The BSD 3-Clause License.
*/

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Veles\Cache\Cache;
use Veles\Cache\Drivers\MemcacheAdapter;
use Veles\Cache\Drivers\MemcachedAdapter;

/**
* Class MemcacheVsMemcachedSave
* @author  Yancharuk Alexander <alex@itvault.info>
*/
class MemcacheVsMemcachedSave extends TestApplication
{
	protected static $repeats = 10000;

	final public static function run()
	{
		self::initCache();

		$repeats = self::getRepeats();
		$bar = new CliProgressBar($repeats);
		$data = range(0, 99);
		Cache::setAdapter('Memcache');
		for ($i = 0; $i <= $repeats; ++$i) {
			$key = sha1(uniqid());
			Timer::start();
			Cache::set($key, $data);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Memcache', Timer::get());

		$bar = new CliProgressBar($repeats);
		Timer::reset();
		Cache::setAdapter('Memcached');
		for ($i = 0; $i <= $repeats; ++$i) {
			$key = sha1(uniqid());
			Timer::start();
			Cache::set($key, $data);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Memcached', Timer::get());
		Cache::clear();
	}

	private static function initCache()
	{
		MemcacheAdapter::instance()->addServer('localhost', 11211);
		MemcachedAdapter::instance()->addServer('localhost', 11211);
	}
}
