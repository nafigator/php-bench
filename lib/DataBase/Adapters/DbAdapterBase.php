<?php
/**
 * Base db-adapter class
 *
 * @file      PdoConnection.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @copyright © 2012-2015 Alexander Yancharuk <alex at itvault at info>
 * @date      2013-12-31 15:44
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace DataBase\Adapters;

use DataBase\ConnectionPools\ConnectionPool;
use Veles\Traits\SingletonInstance;

/**
 * Class DbAdapterBase
 *
 * Base class for Db adapters
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class DbAdapterBase
{
	/** @var ConnectionPool */
	protected static $pool;
	/** @var  \PDO */
	protected static $connection;
	/** @var  string */
	protected static $connection_name;

	use SingletonInstance;

	/**
	 * Add connection pool
	 *
	 * @param ConnectionPool $pool
	 */
	public static function setPool(ConnectionPool $pool)
	{
		static::$pool = $pool;
		static::$connection_name = $pool->getDefaultConnectionName();
	}

	/**
	 * Get connection pool
	 *
	 * @return ConnectionPool $pool
	 */
	public static function getPool()
	{
		return static::$pool;
	}

	/**
	 * Set default connection
	 *
	 * @param string $name Connection name
	 *
	 * @return $this
	 */
	public function setConnection($name)
	{
		static::$connection_name = $name;
		static::$connection = null;

		return $this;
	}

	/**
	 * Get default connection resource
	 *
	 * return \PDO
	 */
	public function getConnection()
	{
		if (null === static::$connection) {
			$conn = static::$pool->getConnection(static::$connection_name);
			static::$connection = (null === $conn->getResource())
				? $conn->create()
				: $conn->getResource();
		}

		return static::$connection;
	}
}
