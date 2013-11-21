<?php
/**
 * Dependency exception class
 *
 * @file    DependencyException.php
 *
 * PHP version 5.3.9+
 *
 * @author  Yancharuk Alexander <alex@itvault.info>
 * @date    Чтв Ноя 21 15:28:44 2013
 * @copyright The BSD 3-Clause License.
 */

namespace Application;


use Veles\Tools\CliColor;

/**
 * Class DependencyException
 *
 * @package Application
 */
class DependencyException extends \Exception
{
	/**
	 * Prints warning about unresolved dependencies
	 *
	 * @param string $class_names Unresolved classes and extensions names
	 */
	final public function __construct($class_names)
	{
		$warning = new CliColor('red', array('bold'));
		$str = "WARNING!\nFound unresolved dependencies:\n\n";
		echo $warning->setString($str);

		$dependencies = new CliColor('white', array('bold'));
		echo $dependencies->setString("$class_names\n");

		$warning->setStyle(array('default'));
		$str = "Please, install proper extensions for test completion.\n";
		echo $warning->setString($str);
	}
}