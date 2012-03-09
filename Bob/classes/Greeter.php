<?php namespace bar;

class Greeter extends \Alice\core\Greeter
{
	public static function sayHello($name)
	{
		return \ucfirst(parent::sayHello($name)).'.';
	}

} # class

// for demo purposes
echo "Loaded \\bar\\Greeter<br>";