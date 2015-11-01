<?php
/**
 * Check what is faster MySQL, MySQLi or PDO on simple insert queries
 *
 * @file      MysqlVsMysqliVsPdoWrite.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2015 Yancharuk Alexander
 * @date      Fri Oct 02 12:33:15 2015
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\DbConnectException;
use Application\DbQueryException;
use Application\TestApplication;
use mysqli;
use PDO;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;

/**
 * Class MysqlVsMysqliVsPdoWrite
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class MysqlVsMysqliVsPdoWrite extends TestApplication
{
	protected $class_dependencies = ['PDO', 'MySQLi'];
	protected $ext_dependencies = ['pdo_mysql', 'mysqli', 'mysql'];

	private $user = 'root';
	private $host = 'localhost';
	private $password = '';
	private $database = 'php_bench_test';

    protected $repeats = 1000;
	protected $result_format = "%-25s%-16s%-16s%-16s\n";

	public function run()
	{
		$this->prepareTables();
		$repeats = $this->getRepeats();

		$bar = new CliProgressBar($repeats);
		$value = uniqid();
		$sql = "INSERT INTO test (txt) VALUES ('$value')";

		$link = @mysql_connect($this->host, $this->user, $this->password);
		@mysql_select_db($this->database);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			@mysql_query($sql, $link);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('MySQL', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$mysqli = new mysqli(
			$this->host, $this->user, $this->password, $this->database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$mysqli->query($sql);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('MySQLi', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$link = mysqli_connect(
			$this->host, $this->user, $this->password, $this->database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			mysqli_query($link, $sql);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('Non-object MySQLi', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$dsn = 'mysql:dbname=' . $this->database . ';'
			. 'host=' . $this->host . ';'
			. 'charset=utf8';
		$pdo = new PDO($dsn, $this->user, $this->password);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$pdo->query($sql);
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('PDO', Timer::get());

		$this->cleanup();
	}

	/**
	 * Prepares tables with data for test
	 *
	 * @throws \Application\DbQueryException
	 * @throws \Application\DbConnectException
	 */
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
		$mysqli->query("
			CREATE TABLE IF NOT EXISTS test (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				txt CHAR(50) NOT NULL DEFAULT ''
			) ENGINE INNODB
		");
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}

		$arr = [];
		$i = 0;
		while (++$i <= 1000) {
			$arr[] = uniqid('test-value::');
		}

		$values = implode("'),('", $arr);
		$sql = "INSERT INTO test (txt) VALUES ('$values')";
		$mysqli->query($sql);
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}
	}

	/**
	 * Cleanup database
	 *
	 * @throws \Application\DbQueryException
	 * @throws \Application\DbConnectException
	 */
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
