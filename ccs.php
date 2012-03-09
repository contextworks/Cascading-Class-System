<?php namespace app;

// make sure EXT is defined
if ( ! \defined('EXT'))
{
	\define('EXT', '.php');
}

interface CCS_Edition1
{
	/**
	 * Defines modules with which the autoloaded will work with. Modules are an
	 * array of paths pointing to namespaces. Each namespace must be unique, 
	 * except when using the namespace "app" which may be mapped to any number
	 * of paths.
	 *
	 * @param array modules
	 */
	static function modules(array $modules);

	/**
	 * @param string class name with namespace
	 * @return bool successfully loaded?
	 */
	static function auto_load($class);
	
} # interface
	
/**
 * Cascading Class System, Edition 1
 *
 * This class is an example. Since it doesn't use caching it is recomended you
 * use it as reference and implement your own making use of caching. 
 *
 * @version 1.0
 */
final class Autoloader implements CCS_Edition1
{
	/**
	 * System module paths.
	 *
	 * @var array paths
	 */
	private static $modules;
	
	/**
	 * System namespaces
	 *
	 * @var array namespaces to path association
	 */
	private static $namespaces;
	
	/**
	 * Defines modules with which the autoloaded will work with. Modules are an
	 * array of paths pointing to namespaces. Each namespace must be unique, 
	 * except when using the namespace "app" which may be mapped to any number
	 * of paths.
	 *
	 * @param array modules
	 */
	public static function modules(array $modules)
	{
		self::$modules = $modules;

		// namespace mapping
		self::$namespaces = \array_change_key_case(\array_flip($modules), CASE_LOWER);
		if (isset(self::$namespaces['app']))
		{
			// we consider the app value special, so it's invalid for our
			// namespace mapping
			unset(self::$namespaces['app']);
		}
	}

	/**
	 * @param string class name with namespace
	 * @return bool successfully loaded?
	 */
	public static function auto_load($class)
	{	
		$class_name = \ltrim($class, '\\');
		
		if ($ns_pos = \strripos($class_name, '\\')) 
		{
			$namespace = \strtolower(\substr($class_name, 0, $ns_pos));
			$class_name = \strtolower(\substr($class_name, $ns_pos + 1));
		}
		else # class belongs to global namespace
		{
			// we don't handle classes of the global namespace
			return false;
		}
		
		if ($namespace === 'app')
		{
			$target = DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR
			      . \str_replace('_', DIRECTORY_SEPARATOR, $class_name).EXT;

			foreach (self::$modules as $path => $ns)
			{
				if (\file_exists($path.$target))
				{
					// found a matching file
					require $path.$target;

					if ($ns !== 'app')
					{
						// alias to app namespace
						\class_alias($ns.'\\'.$class_name, $class);
					}

					// success
					return true;
				}
			}

			// didn't find the file
			return false;
		}
		else # non app namespace
		{
			require self::$namespaces[$namespace].DIRECTORY_SEPARATOR
			      . 'classes'.DIRECTORY_SEPARATOR
			      . \str_replace('_', DIRECTORY_SEPARATOR, $class_name).EXT;

			// success
			return true;
		}
	}
	
} # class

