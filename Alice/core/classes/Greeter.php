<?php namespace Alice\core;

class Greeter
{
	public static function sayHello($name)
	{
		return \app\Text::hello(time())." $name";
	}

} # class

// for demo purposes
echo "Loaded \\Alice\\core\\Greeter<br>";