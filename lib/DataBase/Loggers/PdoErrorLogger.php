<?php
/**
 * Class for logging PDO errors
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

namespace DataBase\Loggers;

use SplSubject;

/**
 * Class PdoErrorLogger
 *
 * Класс-подписчик PDO-адаптера. Предназначен для логгирования ошибок
 *
 * @author  Alexander Yancharuk <alex at itvault dot info>
 */
class PdoErrorLogger implements \SplObserver
{
	/** @var  string */
	private $path;

	/**
	 * Установка пути к логу
	 *
	 * @param string $path  Путь к логу
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Получение пути к логу
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Получение обновления
	 *
	 * @param SplSubject $subject
	 */
	public function update(SplSubject $subject)
	{
		/** @noinspection PhpUndefinedMethodInspection */
		/** @var \PDO $conn */
		$conn = $subject->getConnection();
		/** @noinspection PhpUndefinedMethodInspection */
		$conn_err = $conn->errorCode();
		/** @noinspection PhpUndefinedMethodInspection */
		/** @var \PdoStatement $stmt */
		$stmt = $subject->getStmt();
		/** @noinspection PhpUndefinedMethodInspection */
		$stmt_err = $stmt->errorCode();

		if ('00000' === $conn_err && '00000' === $stmt_err)
			return;

		$error_info = ('00000' === $conn_err)
			? implode('; ', $stmt->errorInfo()) . PHP_EOL
			: implode('; ', $conn->errorInfo()) . PHP_EOL;

		error_log($error_info, 3, $this->getPath());
	}
}
