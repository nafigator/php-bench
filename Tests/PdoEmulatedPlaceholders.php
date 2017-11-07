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

use Application\DbConnectException;
use Application\DbQueryException;
use Application\TestApplication;
use mysqli;
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
	protected $class_dependencies = ['PDO', 'MySQLi'];
	protected $ext_dependencies = ['pdo_mysql', 'mysqli'];

    protected $repeats = 1000;
	private $user = 'root';
	private $host = 'localhost';
	private $password = '';
	private $database = 'php_bench_test';

	public function run()
	{
		$this->prepareTables();
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

		Db::getAdapter()->getResource()
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
		$this->cleanup();
	}

	public function prepareTables()
	{
		$mysqli = new mysqli($this->host, $this->user, $this->password);
		if ($mysqli->connect_errno) {
			throw new DbConnectException(
				"Connect Error ($mysqli->connect_errno)\n$mysqli->connect_error"
			);
		}

		$mysqli->query('CREATE DATABASE IF NOT EXISTS ' . $this->database);
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}

		$mysqli->select_db($this->database);

		$sql = "CREATE TABLE IF NOT EXISTS test (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			txt CHAR(50) NOT NULL DEFAULT ''
		) ENGINE INNODB";
		$mysqli->query($sql);
	}

	public function cleanup()
	{
		$mysqli = new mysqli($this->host, $this->user, $this->password);
		if ($mysqli->connect_errno) {
			throw new DbConnectException(
				"Connect Error ($mysqli->connect_errno)\n$mysqli->connect_error"
			);
		}

		$mysqli->query('DROP DATABASE ' . $this->database);
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}
	}
}
