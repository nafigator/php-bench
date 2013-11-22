<?php
/**
 * Database query exception class
 *
 * @file    DbQueryException.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Чтв Ноя 21 18:05:22 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Application;

use Veles\Tools\CliColor;

/**
 * Class DbQueryException
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
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
		$warning = new CliColor('red', array('bold'));
		$str = "Database query error!\n$msg\n";
		echo $warning->setString($str);
	}
} 