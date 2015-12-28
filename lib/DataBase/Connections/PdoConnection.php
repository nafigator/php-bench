<?php
/**
 * PDO connection class
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

namespace DataBase\Connections;

use Exception;
use PDO;

/**
 * Class PdoConnection
 *
 * Class-container for PDO connection and it's options
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class PdoConnection extends DbConnection
{
	/** @var string */
	protected $dsn;
	/** @var array */
	protected $options;
	/** @var array */
	protected $callbacks = [];

	/**
	 * Create connection
	 *
	 * @return \PDO
	 */
	public function create()
	{
		$this->resource = new PDO(
			$this->getDsn(), $this->getUserName(),
			$this->getPassword(), $this->getOptions()
		);

		if ([] === $this->callbacks) {
			return $this->resource;
		}

		foreach ($this->callbacks as $call) {
			call_user_func_array(
				[$this->resource, $call['method']], $call['arguments']
			);
		}

		return $this->resource;
	}

	/**
	 * Set connection DSN (Data Source Name)
	 *
	 * @param string $dsn
	 *
	 * @return $this
	 */
	public function setDsn($dsn)
	{
		$this->dsn = $dsn;
		return $this;
	}

	/**
	 * Get connection DSN (Data Source Name)
	 *
	 * @throws Exception
	 *
	 * @return string
	 */
	public function getDsn()
	{
		if (null === $this->dsn) {
			throw new Exception('Connection DSN not set.');
		}
		return $this->dsn;
	}

	/**
	 * Set connection options
	 *
	 * @param array $options
	 *
	 * @return $this
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * Get connection options
	 *
	 * @throws Exception
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Get connection callbacks
	 *
	 * @return array
	 */
	public function getCallbacks()
	{
		return $this->callbacks;
	}

	/**
	 * Save calls for future invocation
	 *
	 * @param string $method Method name that should be called
	 * @param array $arguments Method arguments
	 *
	 * @return $this
	 */
	public function setCallback($method, array $arguments)
	{
		$this->callbacks[] = [
			'method'    => $method,
			'arguments' => $arguments
		];
		return $this;
	}
}
