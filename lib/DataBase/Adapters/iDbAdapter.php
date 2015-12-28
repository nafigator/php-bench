<?php
/**
 * Database adapter interface
 *
 * @file      iDbAdapter.php
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

/**
 * Interface iDbAdapter
 *
 * Db adapters interface
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
interface iDbAdapter
{
	/**
	 * Get value from table row
	 *
	 * @param string $sql SQL-query
	 * @param array $params Query values
	 * @param string|null $types Placeholders types
	 * @return string
	 */
	public function value($sql, array $params, $types);

	/**
	 * Get table row
	 *
	 * @param string $sql SQL-query
	 * @param array $params Query values
	 * @param string|null $types Placeholders types
	 * @return array
	 */
	public function row($sql, array $params, $types);

	/**
	 * Get result collection
	 *
	 * @param string $sql SQL-query
	 * @param array $params Query values
	 * @param string|null $types Placeholders types
	 * @return mixed
	 */
	public function rows($sql, array $params, $types);

	/**
	 * Transaction initialization
	 *
	 * @return bool
	 */
	public function begin();

	/**
	 * Transaction rollback
	 *
	 * @return bool
	 */
	public function rollback();

	/**
	 * Commit transaction
	 *
	 * @return bool
	 */
	public function commit();

	/**
	 * Launch non-SELECT query
	 *
	 * @param string $sql Non-SELECT SQL-query
	 * @param array $params Query values
	 * @param string|null $types Placeholders types
	 * @return bool
	 */
	public function query($sql, array $params, $types);

	/**
	 * Get last saved ID
	 *
	 * @return int
	 */
	public function getLastInsertId();

	/**
	 * Get found rows quantity
	 *
	 * @return int
	 */
	public function getFoundRows();

	/**
	 * Get PDOStatement
	 *
	 * Used in subscribers for getting error information
	 *
	 * @return mixed
	 */
	public function getStmt();

	/**
	 * Escape variable
	 *
	 * @param string $var
	 * @return string
	 */
	public function escape($var);
}
