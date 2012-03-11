<?php namespace app;

/**
 * Cascading Class System Bridge
 *
 * This class bridges modules along with their classes from normal kohana to 
 * CCS standard classes. When calling bridged classes you should always use
 * the correct namespace qualifier: \kohana\core\HTML, or \app\HTML and NEVER
 * \HTML or \Kohana_HTML that way your code will work perfectly when the global
 * version becomes obsolte. :)
 *
 * @version 1.0
 */
final class Kohana_Autoloader_Bridge
{
	/**
	 * Source / Namespace association array. Sources must be in kohana format.
	 * and global namespace.
	 *
	 * @var array
	 */
	private static $bridges;

	/**
	 * @param array modules
	 */
	public static function bridges(array $bridges)
	{
		self::$bridges = $bridges;
	}
	
	/**
	 * @param string class name
	 * @return bool found class?
	 */
	private static function prefix_check($class_name)
	{
		// check for all prefix classes
		$class_path = \str_replace('_', DIRECTORY_SEPARATOR, $class_name).EXT;
		foreach (self::$bridges as $path => $bridge)
		{
			// check for prefix match
			$prefix_class_path = $path.DIRECTORY_SEPARATOR
				. 'classes'.DIRECTORY_SEPARATOR
				. $bridge['prefix'].DIRECTORY_SEPARATOR
				. $class_path;
			
			if (\file_exists($prefix_class_path))
			{
				if ( ! \class_exists($bridge['prefix'].'_'.$class_name, false))
				{
					// get the prefix version
					require $prefix_class_path;
				}
				
				if ( ! \class_exists("app\\$class_name"))
				{
					// alias to app namespace
					\class_alias($bridge['prefix'].'_'.$class_name, "app\\$class_name");
				}
				
				if ( ! \class_exists($class_name))
				{
					// alias back to global namespace; for compatibility
					\class_alias("app\\$class_name", $class_name);
				}
				
				// success
				return true;
			}
		}
		
		// didn't find it
		return false;
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
			// we assme it's a kohana class; this should never happen when using
			// the bridge. You should always call the app version so the 
			// namespace version is loaded. The only situation this should 
			// happen is when internally kohana calls the class
			$target = DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR
				. \str_replace('_', DIRECTORY_SEPARATOR, $class_name).EXT;
			
			foreach (\array_keys(self::$bridges) as $path)
			{
				if (\file_exists($path.$target))
				{
					// found a matching file
					require $path.$target;

					// success
					return true;
				}
			}
			
			return self::prefix_check($class_name);
		}
		
		if ($namespace === 'app')
		{
			return self::prefix_check($class_name);
		}
		else # non \app namespace
		{
			// compute class path
			$class_path = \str_replace('_', DIRECTORY_SEPARATOR, $class_name).EXT;
			// find the bridge
			foreach (self::$bridges as $path => $bridge)
			{
				if (\strtolower($bridge['namespace']) === $namespace)
				{
					// check for prefix match
					$prefix_class_path = $path.DIRECTORY_SEPARATOR
						. 'classes'.DIRECTORY_SEPARATOR
						. $bridge['prefix'].DIRECTORY_SEPARATOR
						. $class_path;
					
					if (\file_exists($prefix_class_path))
					{
						if ( ! \class_exists($bridge['prefix'].'_'.$class_name, false))
						{
							// get the prefix version
							require $prefix_class_path;
						}
						
						// alias to app namespace
						\class_alias($bridge['prefix'].'_'.$class_name, "$namespace\\$class_name");
						
						if ( ! \class_exists($class_name))
						{
							// alias back to global namespace; for compatibility
							\class_alias("app\\$class_name", $class_name);
						}
						
						// success
						return true;
					}
					
					// it is possible for there to be more bridges mapped to the
					// same namespace so if the code above fails to deliver, we
					// continue the search until all bridges have been tested
				}
			}
			
			// unknown namespace
			return false;
		}
	}
	
	/**
	 * @return array paths
	 */
	public static function paths()
	{
		$paths = \array_keys(self::$bridges);
		$dir_paths = array();
		foreach ($paths as $path)
		{
			$dir_paths .= \rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		}
		
		return $dir_apths;
	}
	
} # class

