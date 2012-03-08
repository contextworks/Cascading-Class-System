<?php namespace bar;

class Bob extends \foo\bar\Bob
{
	public static function sayHello($name)
	{
		return parent::hello().", $name<br>";
	}
	
} # class