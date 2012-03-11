<?php namespace app;

require 'bootstrap.php';

/* What's happening: \foobar\foo\bar\Greeter is loaded and the happyHello method
 * is called which calls the sayHello method defined in \bar\Greeter and the 
 * randomSmilie private method defined in \foobar\foo\bar\Greeter. In 
 * \bar\Greeter the sayHello method calls the sayHello method in 
 * \Alice\core\Greeter which calls the hello method in \bar\Text that
 * overwrites the method in \Alice\text\Text.
 *
 * Complicated? Well you don't have to know all that... all you need to know is 
 * that there's a class somewhere \app\Greeter that you can call and will do 
 * exactly what you want. You don't need to know the implementation or where 
 * everything is implemented just what interface to use.
 *
 * In the example, Alice also doesn't need to know of Henry nor Bob. And as long
 * as Alice doesn't change her method signatures Alice can do whatever updates 
 * she wants to her classes and you can pull the latest version of Alice while
 * still having Greeter do things they way you want.
 */
echo Greeter::happyHello('Alice').'<br>';
// output: Good morning Alice. :)

/* Since this is not in \app the file is loaded directly. So \Alice\text\Text is
 * loaded and hello is called.
 */
echo \alice\text\Text::hello(\time()).'<br>';
// output: <H:m> hi

/* Even though the above was loaded the \app version still points to the correct
 * version in the CCS. ie. \bar\Text
 */
echo Text::hello(\time()).'<br>';
// output: good morning/evening/afternoon

/* File structure
 * ==============
 * (ordered by stacking order)
 *
 * Henry/ (namespace foobar\foo\bar)
 *   classes/
 *     Text.php
 *
 * Bob/ (namespace bar)
 *   classes/
 *     Greeter.php
 *
 * Alice/
 *   core/ (namespace Alice\core)
 *     classes/
 *       Greeter.php
 *   text/ (namespace Alice\text)
 *     classes/
 *       Text.php
 *
 */