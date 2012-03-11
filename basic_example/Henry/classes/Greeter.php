<?php namespace foobar\foo\bar;

class Greeter extends \bar\Greeter
{
	private static function randomSmilie()
	{
		return \rand(1, 2) === 1 ? ':)' : '=]';
	}

	public static function happyHello($name)
	{
		return static::sayHello($name).' '.self::randomSmilie();
	}

} # class

// for demo purposes
echo "Loaded \\foobar\\foo\\bar\\Greeter<br>";