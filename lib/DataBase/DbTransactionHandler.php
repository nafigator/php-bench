<?php
/**
 * Class for transaction management
 *
 * @file      DbTransactionHandler.php
 *
 * PHP version 5.4+
 *
 * @author    Alexander Yancharuk <alex at itvault dot info>
 * @copyright © 2012-2015 Alexander Yancharuk <alex at itvault at info>
 * @date      Срд Апр 23 06:34:47 MSK 2014
 * @license   The BSD 3-Clause License
 *            <https://tldrlegal.com/license/bsd-3-clause-license-(revised)>
 */

namespace DataBase;

/**
 * Class DbTransactionHandler
 *
 * Класс, содержащий функционал транзакций
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class DbTransactionHandler extends DbBase
{
	/**
	 * Инициализация транзакции
	 *
	 * @return bool
	 */
	public static function begin()
	{
		return self::getAdapter()->begin();
	}

	/**
	 * Откат транзакции
	 *
	 * @return bool
	 */
	public static function rollback()
	{
		return self::getAdapter()->rollback();
	}

	/**
	 * Сохранение всех запросов транзакции и её закрытие
	 *
	 * @return bool
	 */
	public static function commit()
	{
		return self::getAdapter()->commit();
	}
}
