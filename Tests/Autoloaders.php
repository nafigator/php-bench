<?php
/**
 * Test for Zend, Symfony, Laravel, Veles autoloaders performance
 *
 * Autoloaders code was slightly adopted for test, but that adaptation does not
 * have huge impact on result.
 *
 * @file      Autoloaders.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex@itvault.info>
 * @date      Tue May 19 05:59:24 2015
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class Autoloaders
 *
 * @author Alexander Yancharuk <alex@itvault.info>
 */
class AutoLoaders extends TestApplication
{
	const NS_SEPARATOR = '\\';
	const PREFIX_SEPARATOR = '_';
	const LOAD_NS = 'namespaces';
	const LOAD_PREFIX = 'prefixes';
	const ACT_AS_FALLBACK = 'fallback_autoloader';
	const AUTOREGISTER_ZF = 'autoregister_zf';

	protected static $fallbackAutoloaderFlag = false;
	protected static $namespaces = array('\Veles' => '\Veles');

    protected static $repeats = 10000;

	final public static function run()
	{
		$repeats = self::getRepeats();

		$class = '\Veles\Config';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$isFallback = self::ZendisFallbackAutoloader();
			if (false !== strpos($class, self::NS_SEPARATOR)) {
				if (self::ZendloadClass($class, self::LOAD_NS)) {
					//return $class;
				} elseif ($isFallback) {
					self::ZendloadClass($class, self::ACT_AS_FALLBACK);
				}
				//return false;
			}
			if (false !== strpos($class, self::PREFIX_SEPARATOR)) {
				if (self::ZendloadClass($class, self::LOAD_PREFIX)) {
					//return $class;
				} elseif ($isFallback) {
					self::ZendloadClass($class, self::ACT_AS_FALLBACK);
				}
				//return false;
			}
			if ($isFallback) {
				self::ZendloadClass($class, self::ACT_AS_FALLBACK);
			}
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Zend', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$file = preg_replace('/\\\|_(?!.+\\\)/', DIRECTORY_SEPARATOR, $class) . '.php';
			if (false !== ($full_path = stream_resolve_include_path($file)))
				/** @noinspection PhpIncludeInspection */
				//require $full_path;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Veles', Timer::get());
	}

	public static function ZendisFallbackAutoloader()
	{
		return self::$fallbackAutoloaderFlag;
	}

	public static function ZendloadClass($class, $type)
	{
		if (!in_array($type, array(self::LOAD_NS, self::LOAD_PREFIX, self::ACT_AS_FALLBACK))) {
			require_once __DIR__ . '/Exception/InvalidArgumentException.php';
			throw new Exception\InvalidArgumentException();
		}
		// Fallback autoloading
		if ($type === self::ACT_AS_FALLBACK) {
			// create filename
			$filename = self::ZendtransformClassNameToFilename($class, '');
			$resolvedName = stream_resolve_include_path($filename);
			if ($resolvedName !== false) {
				return true;
			}
			return false;
		}
		// Namespace and/or prefix autoloading
		foreach (self::$$type as $leader => $path) {
			if (0 === strpos($class, $leader)) {
				// Trim off leader (namespace or prefix)
				$trimmedClass = substr($class, strlen($leader));
				// create filename
				$filename = self::ZendtransformClassNameToFilename($trimmedClass, $path);
				if (file_exists($filename)) {
					//return include $filename;
				}
			}
		}
		return false;
	}

	public static function ZendtransformClassNameToFilename($class, $directory)
	{
		// $class may contain a namespace portion, in which case we need
		// to preserve any underscores in that portion.
		$matches = array();
		preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);
		$class = (isset($matches['class'])) ? $matches['class'] : '';
		$namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';
		return $directory
		. str_replace(self::NS_SEPARATOR, '/', $namespace)
		. str_replace(self::PREFIX_SEPARATOR, '/', $class)
		. '.php';
	}
}
