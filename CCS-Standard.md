Cascading Class System Standard
==========================================

A Cascading Class System (CCS) is one part of a Cascading File System (CFS). In 
a CCS the same class MAY exist in any number of modules but only the top class 
is ever loaded. eg. If Alice has a class A and class B in her module, and class 
A has an internal dependency to class B, then normally it is very dificult for 
Bob to change Alice's class A. But in a CCS Bob can change class B by adopting 
it in his module and Alice's class A will then use Bob's class B. This is very 
useful when Alice's module is something like a framework, as it allows Bob to 
have full control and customize it to his needs.

A CCS can have any number of modules in a stack and any module may overwrite 
only the modules beneth it. So if the stack is comprised of Bob and Alice, in
that order, any class in Bob overwrites any equivalent class in Alice, but 
classes in Alice can not overwrite classes in Bob.

Standard Definition, Edition 1
==============================

1. The \app namespace is reserved and known as "the project namespace". It is 
meant as an clean artificial global namespace.

2. Each module defines it's code in it's own UNIQUE namespace (eg. \foo\core, 
\bar\database, etc), which is mapped to the module's path. There is no 
relationship between the path and the namespace other then that classes in the 
path use the namespace they are mapped to. Each module must map to an unique 
namespace, or the \app namespace, which is not restricted to one module. Modules 
MAY choose to map to the \app namespace directly if they are SPECIFIC to the 
project (ie. non-reusable, final, or not intended as reusable), to allow for 
simpler code by avoiding redundant namespace notation.

    * no class in the CCS should ever be defined global; the equivalent to 
	classes defined in the global namespace are classes defined in \app

3. The class name MUST map to a directory or file inside the /classes/ directory 
of each module. If EXT is defined it will be used as the mapped file's 
extention. eg. if EXT is defined as ".php" the class Foo_Bar_Foobar maps to the 
classes/foo/bar/foobar.php file, if EXT is not defined by default it will be 
defined as ".php".

4. When a class is loaded:

    * if the namespace is \app then the class is in the project namespace and 
    the autoloader MUST look into the CCS for the first file matching the 
    Class_Name pattern, load it and if the module from which it was loaded was 
    not mapped to the \app namespace define a class_alias for it from the 
    module's namespace to the \app namespace.

    * if the namespace of the requested class is not \app then the class is 
    simply loaded directly from the module matching the namespace.
	
	* if the namespace of the requested class is the global namespace the 
	autoloading process MUST fail imediatly
	
	* the class and namespace are always considered lowercase when computing the
	path; so as to support default PHP behaviour (case insensitive namespace and 
	classes)

5. All logic MUST BE designed to work as if the \app namespace is the ONLY 
namespace that exists. Classes should never rely on any other namespace, not 
even their own namespace. All modules that are not under the \app namespace 
MUST ALWAYS use the \app version of ANY other class they call. If the class 
is making use of a static method within itself it should ALWAYS use the 
(static)[http://php.net/manual/en/language.oop5.late-static-bindings.php]
keyword for methods with public and protected access and the self keyword for 
members with private access, and NEVER the class name; thus allow for extension 
of the class to a different name with out breaking it.

IDE Support
===========

The standard is fully compatible with IDEs. There are a few ways to work with
IDEs including creating a autocomplete provider. The following describes 
only the recommended method of achieving cross-IDE autocomplete.

First off, you should always work in the \app namespace. Modules you define 
directly in the \app namespace will just work with IDEs since the mapping is 
obvious.

When working with non \app files you need to tell the IDE the class can map to
the \app equivalent. To do this we recommend creating a honeypot.php file at the
route of every module that requires it. This duty should fall on the party 
responsible for creating the module, not the users of the module. In it include 
all the mappings you want. The file is not loaded by the system in any way, it's 
merely a trap for the IDEs crawler and serves to wise up it's autocomplete 
system. IDEs like Netbeans will then show the methods when accessing via the 
\app version along with the namespace from which they are from.

That's all there is to it.