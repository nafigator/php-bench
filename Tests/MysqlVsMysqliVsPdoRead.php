<?php
/**
 * Check what is faster MySQL, MySQLi or PDO on simple queries;
 * @file    MysqlVsMysqliVsPdoRead.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 * @date    Fri Sep 27 13:06:09 2013
 * @copyright The BSD 3-Clause License
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
 * Class MysqlVsMysqliVsPdoRead
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class MysqlVsMysqliVsPdoRead extends TestApplication
{
	protected static $class_dependencies = ['PDO', 'MySQLi'];
	protected static $ext_dependencies = ['pdo_mysql', 'mysqli', 'mysql'];

	protected static $repeats = 1000;
	protected static $result_format = "%-30s%-16s%-16s%-16s\n";

	private static $user = 'root';
	private static $host = 'localhost';
	private static $password = '';
	private static $database = 'php_bench_test';

	final public static function run()
	{
		self::prepareTables();
		$repeats = self::getRepeats();

		$sql = 'SELECT txt FROM test';
		$bar = new CliProgressBar($repeats);

		$link = @mysql_connect(self::$host, self::$user, self::$password);
		@mysql_select_db(self::$database);
		for ($i = 1; $i <= $repeats; ++$i) {
			$result = [];
			Timer::start();
			$res = @mysql_query($sql, $link);
			while ($row = @mysql_fetch_assoc($res)) $result[] = $row;
			Timer::stop();
			$bar->update($i);
		}

		@mysql_free_result($res);
		self::addResult('MySQL', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		$mysqli = new mysqli(
			self::$host, self::$user, self::$password, self::$database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			$result = [];
			Timer::start();
			$res = $mysqli->query($sql);
			while ($row = $res->fetch_assoc()) $result[] = $row;
			Timer::stop();
			$bar->update($i);
		}

		$res->free();
		self::addResult('MySQLi', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		$link = mysqli_connect(
			self::$host, self::$user, self::$password, self::$database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			$result = [];
			Timer::start();
			$res = mysqli_query($link, $sql);
			while ($row = mysqli_fetch_row($res)) $result[] = $row;
			Timer::stop();
			$bar->update($i);
		}

		mysqli_close($link);
		self::addResult('Non-object MySQLi', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		$link = mysqli_connect(
			self::$host, self::$user, self::$password, self::$database
		);
		for ($i = 1; $i <= $repeats; ++$i) {
			$result = [];
			Timer::start();
			$res = mysqli_query($link, $sql, MYSQLI_USE_RESULT);
			while ($row = mysqli_fetch_row($res)) $result[] = $row;
			Timer::stop();
			$bar->update($i);
		}

		mysqli_close($link);
		self::addResult('Non-object unbuffered MySQLi', Timer::get());

		$bar = new CliProgressBar($repeats);

		Timer::reset();
		$dsn = 'mysql:dbname=' . self::$database . ';'
			 . 'host=' . self::$host . ';'
			 . 'charset=utf8';
		$pdo = new PDO($dsn, self::$user, self::$password);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		for ($i = 1; $i <= $repeats; ++$i) {
			$result = [];
			Timer::start();
			foreach ($pdo->query($sql) as $row) $result[] = $row;
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('PDO', Timer::get());
		$bar = new CliProgressBar($repeats);

		Timer::reset();
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$stmt = $pdo->query($sql);
			$result = $stmt->fetchAll();
			Timer::stop();
			$bar->update($i);
		}

		self::addResult('PDO fetchAll()', Timer::get());
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
