<?php namespace app;

require 'bootstrap.php';

/* There is no class app\Bob, but there is a class bar\Bob and foo\bar\Bob. The
 * class foo\bar\Bob doesn't extent anything, but the class bar\Bob does extent
 * foo\bar\Bob
 */
echo Bob::sayHello('Alice');
