<?php
/**
 * Base class for database interaction
 *
 * @file      DbBase.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @copyright © 2012-2017 Alexander Yancharuk <alex at itvault at info>
 * @date      Срд Апр 23 06:34:47 MSK 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace DataBase;

use Exception;
use DataBase\Adapters\DbAdapterBase;
use DataBase\Adapters\iDbAdapter;

/**
 * Class DbBase
 *
 * Базовый класс для работы с базой данных
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class DbBase
{
	/** @var iDbAdapter|DbAdapterBase */
	protected static $adapter;
	/** @var  mixed */
	protected static $connection;
	/** @var  string */
	protected static $connection_name;

	/**
	 * Сохраняем имя класса адаптера для последующей инициализации
	 * Будет инициализирован при первом запросе данных из базы
	 *
	 * @param iDbAdapter $adapter Adapter
	 * @see Db::getAdapter
	 */
	public static function setAdapter(iDbAdapter $adapter)
	{
		self::$adapter = $adapter;
	}

	/**
	 * Инстанс адаптера
	 *
	 * @throws Exception
	 * @return iDbAdapter|DbAdapterBase
	 */
	public static function getAdapter()
	{
		if (null === self::$adapter) {
			throw new Exception('Adapter not set!');
		}

		return self::$adapter;
	}

	/**
	 * Выбор соединения
	 *
	 * @param string $name Имя соединения
	 * @return DbAdapterBase
	 */
	public static function connection($name)
	{
		return self::getAdapter()->setConnection($name);
	}

	/**
	 * Получение последнего сохранённого ID
	 *
	 * @return int
	 */
	public static function getLastInsertId()
	{
		return self::getAdapter()->getLastInsertId();
	}

	/**
	 * Получение кол-ва строк в результате
	 *
	 * @return int
	 */
	public static function getFoundRows()
	{
		return self::getAdapter()->getFoundRows();
	}

	/**
	 * Escaping variable
	 *
	 * @param string $var
	 * @return string
	 */
	public static function escape($var)
	{
		return self::getAdapter()->escape($var);
	}
}
