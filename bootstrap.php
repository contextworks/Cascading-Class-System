<?php \error_reporting(-1);

\define('DOCROOT', \realpath(\dirname(__FILE__)).DIRECTORY_SEPARATOR);

// standard's example implementation
require 'ccs.php';

// register autoloader
\spl_autoload_register(array('\\app\\Autoloader', 'auto_load'));

// configure
\app\Autoloader::modules
	(
		array
		(
	#         module path => namespace
			DOCROOT.'Henry' => 'foobar\\foo\\bar',
			DOCROOT.'Bob' => 'bar',
			DOCROOT.'Alice/core' => 'alice\\core',
			DOCROOT.'Alice/text' => 'aLiCe\\text',
		)
	);

// make the output pretty
echo '<pre style="font-family: monospace;">';