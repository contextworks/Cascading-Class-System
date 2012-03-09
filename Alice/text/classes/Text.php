<?php namespace alice\text;

class Text
{
	public static function hello($time)
	{
 		return \date('H:m', $time).' hi';
	}

} # class

// for demo purposes
echo "Loaded \\alice\\text\\Text<br>";