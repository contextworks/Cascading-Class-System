<?php # this file is a IDE honeypot :)

// HOWTO: 
// declare the class you want autocomplete for then extend the class you need it 
// to map methods to; IDEs will crawl this file and make the connection--this 
// won't affect autoloading or the application in any way (the file isn't used)

// [!!] changes may not take effect imediatly

namespace app;

class Greeter extends alice\core\Greeter {}
