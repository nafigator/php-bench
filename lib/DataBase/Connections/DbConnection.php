<?php
/**
 * Base connection class
 *
 * @file      DbConnection.php
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

/**
 * Class DbConnection
 *
 * Базовый класс-контейнер для хранения общих для всех соединений параметров
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
abstract class DbConnection
{
	/** @var string */
	protected $user_name;
	/** @var string */
	protected $password;
	/** @var string */
	protected $name;
	/** @var mixed */
	protected $resource;

	/**
	 * @param string $name Unique connection name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * Connection create
	 *
	 * Must be realized in child classes
	 *
	 * @return mixed
	 */
	abstract public function create();

	/**
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * @param mixed $resource
	 */
	public function setResource($resource)
	{
		$this->resource = $resource;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @throws Exception
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @throws Exception
	 * @return string
	 */
	public function getUserName()
	{
		return $this->user_name;
	}

	/**
	 * @param string $user_name
	 * @return $this
	 */
	public function setUserName($user_name)
	{
		$this->user_name = $user_name;
		return $this;
	}
}
