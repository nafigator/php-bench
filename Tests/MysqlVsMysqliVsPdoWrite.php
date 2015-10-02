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
	protected static $class_dependencies = ['PDO', 'MySQLi'];
	protected static $ext_dependencies = ['pdo_mysql', 'mysqli', 'mysql'];

	private static $user = 'root';
	private static $host = 'localhost';
	private static $password = '';
	private static $database = 'php_bench_test';

    protected static $repeats = 10000;
	protected static $result_format = "%-25s%-16s%-16s%-16s\n";

	final public static function run()
	{
		self::prepareTables();
		$repeats = self::getRepeats();

		$bar = new CliProgressBar($repeats);
		$value = uniqid();
		$sql = "INSERT INTO test (txt) VALUES ('$value')";

		$link = @mysql_connect(self::$host, self::$user, self::$password);
		@mysql_select_db(self::$database);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			@mysql_query($sql, $link);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('MySQL', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$mysqli = new mysqli(
			self::$host, self::$user, self::$password, self::$database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$mysqli->query($sql);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('MySQLi', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$link = mysqli_connect(
			self::$host, self::$user, self::$password, self::$database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			mysqli_query($link, $sql);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('Non-object MySQLi', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		$dsn = 'mysql:dbname=' . self::$database . ';'
			. 'host=' . self::$host . ';'
			. 'charset=utf8';
		$pdo = new PDO($dsn, self::$user, self::$password);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$pdo->query($sql);
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('PDO', Timer::get());

		self::cleanup();
	}

	/**
	 * Prepares tables with data for test
	 *
	 * @throws \Application\DbQueryException
	 * @throws \Application\DbConnectException
	 */
	final public static function prepareTables()
	{
		$mysqli = new mysqli(self::$host, self::$user, self::$password);
		if ($mysqli->connect_errno) {
			throw new DbConnectException(
				"Connect Error ($mysqli->connect_errno)\n$mysqli->connect_error"
			);
		}

		$mysqli->query('CREATE DATABASE IF NOT EXISTS ' . self::$database);
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}

		$mysqli->select_db(self::$database);
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
	final public static function cleanup()
	{
		$mysqli = new mysqli(self::$host, self::$user, self::$password);
		if ($mysqli->connect_errno) {
			throw new DbConnectException(
				"Connect Error ($mysqli->connect_errno)\n$mysqli->connect_error"
			);
		}

		$mysqli->query('DROP DATABASE ' . self::$database);
		if ($mysqli->errno) {
			throw new DbQueryException(
				"Query Error ($mysqli->errno)\n$mysqli->error"
			);
		}
	}
}
