<?php
/**
 * Class for connection pool management
 *
 * @file      PdoAdapter.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @copyright © 2012-2017 Alexander Yancharuk <alex at itvault at info>
 * @date      2013-12-31 15:44
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace DataBase\ConnectionPools;

use DataBase\Connections\PdoConnection;

/**
 * Class ConnectionPool
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class ConnectionPool
{
	/** @var array */
	protected $pool;
	/** @var  string */
	protected $conn_name;

	/**
	 * @return string
	 */
	public function getDefaultConnectionName()
	{
		return $this->conn_name;
	}

	/**
	 * Add connection to connection pool
	 *
	 * @param PdoConnection $conn
	 * @param bool $default Flag is this connection default or not
	 * @return $this
	 * @see DbConnection
	 */
	public function addConnection(PdoConnection $conn, $default = false)
	{
		$this->pool[$conn->getName()] = $conn;

		if ($default) {
			$this->conn_name = $conn->getName();
		}
		return $this;
	}

	/**
	 * Get connection class by name
	 *
	 * @param string $name Connection name
	 * @return PdoConnection|null
	 * @see DbConnection
	 */
	public function getConnection($name)
	{
		return isset($this->pool[$name]) ? $this->pool[$name] : null;
	}
}
