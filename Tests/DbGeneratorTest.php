<?php
/**
 * Check performance of identical db-classes which reach data collection of 1000
 * elements. One of them uses generators, other not.
 *
 * @file      DbGeneratorTest.php
 *
 * PHP version 5.4+
 *
 * @author    Yancharuk Alexander <alex at itvault dot info>
 * @copyright © 2013-2020 Yancharuk Alexander
 * @date      Mon Dec 28 17:37:50 2015
 * @license   The BSD 3-Clause License.
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace Tests;

use Application\DbConnectException;
use Application\DbQueryException;
use DataBase\Adapters\PdoAdapter;
use DataBase\ConnectionPools\ConnectionPool;
use DataBase\Connections\PdoConnection;
use DataBase\Db;
use mysqli;
use PDO;
use Veles\DataBase\Db as ODb;
use Veles\Tools\CliProgressBar;
use Veles\Tools\Timer;
use Application\TestApplication;

/**
 * Class DbGeneratorTest
 *
 * @author Yancharuk Alexander <alex at itvault dot info>
 */
class DbGeneratorTest extends TestApplication
{
	protected $class_dependencies = ['PDO', 'MySQLi'];
	protected $ext_dependencies = ['pdo_mysql', 'mysqli'];

	private $user = 'root';
	private $host = 'localhost';
	private $password = '';
	private $database = 'php_bench_test';

    protected $repeats = 1000;

	public function run()
	{
		$this->prepareTables();
		$repeats = $this->getRepeats();

		$sql = 'SELECT txt FROM test';

		$bar = new CliProgressBar($repeats);
		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			$result = ODb::rows($sql);
			foreach ($result as $value) {}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('non-generator', Timer::get());

		Timer::reset();
		$bar = new CliProgressBar($repeats);

		// Db class initialization
		$pool = new ConnectionPool();
		$conn = new PdoConnection('master');

		$conn->setDsn("mysql:host=localhost;dbname=$this->database;charset=utf8")
			->setUserName('root')
			->setPassword('')
			->setOptions([
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false
			]);
		$pool->addConnection($conn, true);
		PdoAdapter::setPool($pool);
		Db::setAdapter(PdoAdapter::instance());

		for ($i = 1; $i <= $repeats; ++$i) {
			Timer::start();
			/** @var \Closure $result */
			$result = Db::rows($sql);
			foreach ($result() as $value) {}
			Timer::stop();
			$bar->update($i);
		}

		$this->addResult('generator', Timer::get());

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
		while (++$i <= 5000) {
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
