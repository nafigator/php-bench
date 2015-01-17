<?php
/**
 * Database query exception class
 *
 * @file    DbQueryException.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 * @date    Чтв Ноя 21 18:05:22 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Application;

use Veles\Tools\CliColor;

/**
 * Class DbQueryException
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class DbQueryException extends \Exception
{
	/**
	 * Prints custom database query error message
	 *
	 * @param string $msg Error message
	 */
	final public function __construct($msg)
	{
		$warning = new CliColor('red', ['bold']);
		$str = "Database query error!\n$msg\n";
		echo $warning->setString($str);
	}
}
