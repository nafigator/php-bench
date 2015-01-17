<?php
/**
 * Database connection exception class
 *
 * @file    DbConnectException.php
 *
 * PHP version 5.4+
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 * @date    Чтв Ноя 21 17:50:52 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Application;

use Veles\Tools\CliColor;

/**
 * Class DbConnectException
 *
 * @author  Yancharuk Alexander <alex at itvault dot info>
 */
class DbConnectException extends \Exception
{
	/**
	 * Prints custom connection error message
	 *
	 * @param string $msg Error message
	 */
	final public function __construct($msg)
	{
		$warning = new CliColor('red', ['bold']);
		$str = "Database connection error!\n$msg\n";
		echo $warning->setString($str);
	}
}
