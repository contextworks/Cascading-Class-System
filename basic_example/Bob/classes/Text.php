<?php namespace bar;

class Text
{
	public static function hello($time)
	{
 		if(\date("H", $time) < 12)
 		{
 			return "good morning";
 		}
 		elseif(\date("H", $time) >= 12 && \date("H", $time) < 18)
 		{
			return "good afternoon";
		} 
		elseif (\date("H", $time) >= 18) 
		{
 			return "good evening";
 		}
	}

} # class

// for demo purposes
echo "Loaded \\bar\\Text<br>";