<?php
/**
 * PDO adapter class
 *
 * @file      PdoAdapter.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @copyright © 2012-2019 Alexander Yancharuk <alex at itvault at info>
 * @date      2013-12-31 15:44
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace DataBase\Adapters;

use PDO;
use DataBase\Exceptions\DbException;

/**
 * Class PdoAdapter
 *
 * Adapter PDO extension
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class PdoAdapter extends DbAdapterBase implements iDbAdapter
{
	// Save statement for ability to get error information
	/** @var  \PDOStatement */
	private $stmt;

	private $type = [
	   'i' => PDO::PARAM_INT,
	   'd' => PDO::PARAM_STR,
	   's' => PDO::PARAM_STR,
	   'b' => PDO::PARAM_LOB
	];

	private function bindParams(array $params, $types)
	{
		foreach ($params as $key => $param) {
			$type = isset($this->type[$types[$key]])
				? $this->type[$types[$key]]
				: PDO::PARAM_STR;
			// Placeholder numbers begins from 1
			$this->stmt->bindValue($key + 1, $param, $type);
		}
	}

	private function prepare($sql, array $params, $types)
	{
		$this->stmt = $this->getConnection()->prepare($sql);

		if (null === $types) {
			$this->stmt->execute($params);
		} else {
			$this->bindParams($params, $types);
			$this->stmt->execute();
		}
	}

	/**
	 * Throw DbException with query info
	 *
	 * @param string        $sql
	 * @param array         $params
	 * @param \PDOException $e
	 *
	 * @throws DbException
	 */
	private function throwExceptionWithInfo($sql, array $params, \PDOException $e)
	{
		$exception = new DbException($e->getMessage(), (int) $e->getCode(), $e);
		$exception->setSql($sql);
		$exception->setParams($params);

		throw $exception;
	}

	/**
	 * Get value from table row
	 *
	 * @param string      $sql    SQL-query
	 * @param array       $params Query values
	 * @param string|null $types  Placeholders types
	 *
	 * @return mixed
	 */
	public function value($sql, array $params, $types)
	{
		$result = '';

		try {
			$this->prepare($sql, $params, $types);
			$result = $this->stmt->fetchColumn();
		} catch (\PDOException $e) {
			$this->throwExceptionWithInfo($sql, $params, $e);
		}

		return $result;
	}

	/**
	 * Get table row
	 *
	 * @param string      $sql    SQL-query
	 * @param array       $params Query values
	 * @param string|null $types  Placeholders types
	 *
	 * @return mixed
	 */
	public function row($sql, array $params, $types)
	{
		$result = [];

		try {
			$this->prepare($sql, $params, $types);
			$result = $this->stmt->fetch();
		} catch (\PDOException $e) {
			$this->throwExceptionWithInfo($sql, $params, $e);
		}

		return $result;
	}

	/**
	 * Get result collection
	 *
	 * @param string      $sql    SQL-query
	 * @param array       $params Query values
	 * @param string|null $types  Placeholders types
	 *
	 * @return mixed
	 */
	public function rows($sql, array $params, $types)
	{
		$result = [];

		try {
			$this->prepare($sql, $params, $types);
			$result = function () {
				yield $this->stmt->fetch();
			};
		} catch (\PDOException $e) {
			$this->throwExceptionWithInfo($sql, $params, $e);
		}

		return $result;
	}

	/**
	 * Transaction initialization
	 *
	 * @return bool
	 * @throws DbException
	 */
	public function begin()
	{
		try {
			$result = $this->getConnection()->beginTransaction();
		} catch (\PDOException $e) {
			throw new DbException($e->getMessage(), (int) $e->getCode(), $e);
		}
		return $result;
	}

	/**
	 * Transaction rollback
	 *
	 * @return bool
	 * @throws DbException
	 */
	public function rollback()
	{
		try {
			$result = $this->getConnection()->rollBack();
		} catch (\PDOException $e) {
			throw new DbException($e->getMessage(), (int) $e->getCode(), $e);
		}
		return $result;
	}

	/**
	 * Commit transaction
	 *
	 * @return bool
	 * @throws DbException
	 */
	public function commit()
	{
		try {
			$result = $this->getConnection()->commit();
		} catch (\PDOException $e) {
			throw new DbException($e->getMessage(), (int) $e->getCode(), $e);
		}
		return $result;
	}

	/**
	 * Launch non-SELECT query
	 *
	 * @param string      $sql    Non-SELECT SQL-query
	 * @param array       $params Query values
	 * @param string|null $types  Placeholders types
	 *
	 * @return bool
	 */
	public function query($sql, array $params, $types)
	{
		$result = false;

		try {
			if (empty($params)) {
				return (bool)$this->getConnection()->query($sql);
			}

			$this->stmt = $this->getConnection()->prepare($sql);

			if (null === $types) {
				$result = $this->stmt->execute($params);
			} else {
				$this->bindParams($params, $types);
				$result = $this->stmt->execute();
			}
		} catch (\PDOException $e) {
			$this->throwExceptionWithInfo($sql, $params, $e);
		}

		return $result;
	}

	/**
	 * Get last saved ID
	 *
	 * @return int
	 * @throws DbException
	 */
	public function getLastInsertId()
	{
		try {
			$result = (int) $this->getConnection()->lastInsertId();
		} catch (\PDOException $e) {
			throw new DbException($e->getMessage(), (int) $e->getCode(), $e);
		}
		return $result;
	}

	/**
	 * Get found rows quantity
	 *
	 * @return int
	 * @throws DbException
	 */
	public function getFoundRows()
	{
		return (int) $this->value('SELECT FOUND_ROWS()', [], null);
	}

	/**
	 * Get PDOStatement
	 *
	 * Used in subscribers for getting error information
	 *
	 * @return mixed
	 */
	public function getStmt()
	{
		return $this->stmt;
	}

	/**
	 * Escape variable
	 *
	 * @param string $var
	 *
	 * @return string
	 * @throws DbException
	 */
	public function escape($var)
	{
		try {
			$result = $this->getConnection()->quote($var);
		} catch (\PDOException $e) {
			throw new DbException($e->getMessage(), (int) $e->getCode(), $e);
		}
		return $result;
	}
}
