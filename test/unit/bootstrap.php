<?php
/**
 * Test bootstrap for Gignite\StateMachine
 * 
 * @package     StateMachine
 * @category    Test
 * @author      Luke Morton
 * @copyright   Gignite, 2012
 */

define('ROOT_PATH', realpath(__DIR__.'/../../').'/');
require(ROOT_PATH.'test/unit/Gignite/Util/TestDox/ResultPrinter/Text.php');

/**
 * PSR-0 Compatible autoloader
 *
 * @param string $class
 */
spl_autoload_register(function ($class)
{
	$class = ltrim($class, '\\');

	$fileName  = '';
	$namespace = '';

	if ($lastNsPos = strripos($class, '\\'))
	{
		$namespace = substr($class, 0, $lastNsPos);
		$class     = substr($class, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}

	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

	$fileGlobs = array(
		ROOT_PATH.'classes/'.$fileName,
		ROOT_PATH.'test/unit/'.$fileName,
	);

	// Load from module classes first and foremost
	foreach ($fileGlobs as $_glob)
	{
		$filePaths = glob($_glob);

		if (count($filePaths) > 0)
		{
			require current($filePaths);
			break;
		}
	}
});