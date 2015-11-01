<?php
/**
 * PDO emulated placeholders performance test
 *
 * @file      PdoEmulatedPlaceholders.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @date      Tue Jan 28 11:59:34 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\TestApplication;
use PDO;
use Veles\DataBase\Adapters\PdoAdapter;
use Veles\DataBase\Db;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class PdoEmulatedPlaceholders
 *
 * @author Alexander Yancharuk <alex at itvault dot info>
 */
class PdoEmulatedPlaceholders extends TestApplication
{
    protected $repeats = 1000;
	private static $database = 'php_bench_test';

	public function run()
	{
		self::prepareTables();
		$repeats = $this->getRepeats();
		$value1 = 'string one';
		$value2 = 'string two';
		$value3 = 'string three';


		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$sql = '
				INSERT INTO test (txt) VALUES
					(?),
					(?),
					(?)
			';
			Db::query($sql, [$value1, $value2, $value3]);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Real', Timer::get());

		PdoAdapter::instance()->getConnection()
			->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		Timer::reset();
		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$sql = '
				INSERT INTO test (txt) VALUES
					(?),
					(?),
					(?)
			';
			Db::query($sql, [$value1, $value2, $value3]);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Emulated', Timer::get());
		self::cleanup();
	}

	public function prepareTables()
	{
		$sql = 'CREATE DATABASE IF NOT EXISTS ' . self::$database;
		Db::query($sql);
		$sql = "CREATE TABLE IF NOT EXISTS test (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			txt CHAR(50) NOT NULL DEFAULT ''
		) ENGINE INNODB";
		Db::query($sql);
	}

	public function cleanup()
	{
		$sql = 'DROP TABLE test';
		Db::query($sql);
	}
}
