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

Namespace UNIQUE-ness
=====================

As per point two above, you'll notice the requirement of namespaces to be 
unique this section clarifies, what that means, why, and how.

UNIQUE there refers to a namespace being unique both in the project, and the 
world. The namespace must not appear anywhere on anything other then this module
even if the place it appears on is a project that does not rely on the system
described here, if it is PHP code, or can interchange calls with PHP code, it is
an invalid namespace, because it is not unique enough.

Recomended pattern is <organization>\<module_name> (eg. myorganization\ccs). You
may also use just <organization> if the organization refers EXCLUSIVELY to
said module. If you are a person, or wish to use the pattern as a person then it
is always <identification>\<module_name> (eg. srcspider\ccs). When using the
single <organization> pattern also take into account that it is not a common 
word (eg. access, style, etc are all bad). Your namespace (that is the first 
segment) should be unique to you and/or your project.

Why? To understand why, you have to first understand what problems name spaces 
are meant to solve and how they solve them. The are three problems:

 * name conflicts with other people's stuff
 * name conflicts with your old stuff
 * name conflicts with your yet to be created stuff
 
To understand how it's solved let's take the example 
id_name\project_name\module_name. 

The id_name solves our first problem. We can safely use any class or function 
name we wish because nobody else has our id_name. It's in a sense like your 
family name. 

However, like you're family name it's not necesarly unique enough. And so we 
have the problem of what happens when you have to manage name conflicts between
your own code? Well that's where the next segments comes in (the 
sub-namespaces). By placing that we narrow the "name space" further. So now it's
something with and id_name and project_name. And that's usually enough for just
about everything, at least for individual needs.

The third segment and onwards usually come into play when responsibility 
branches out. So if you have a "core" team, and a "interface" team, then 
the core team might write in id_name\project_name\core and the cool team in
id_name\project_name\interface. An alternative use is also for reusing common
words for "project names". So if you have a series of projects with a common 
goal you can unify them into a single name, and use intuitive namespaces for 
each. 

Keep in mind, this is all by necesity! If you just have 
id_name\project_name\core then use id_name\project_name instead, there's no 
point in the longer namespace. And if there's no potential namespace conflict 
then don't create useless name space divisions. You'll just make your life 
harder, your namespace more confusing, and potentially end up having "namespace
name problems". :)

One last thing to avoid is patterns such as this: mynamespace\controller\User. 
This is very much not a proper namespace. For one, it fails at the task of a 
namespace since if controller is meant to create a sub space in mynamespace and
User is a controller, then what is a controller for users in a non controller
subspace? If all your controllers are suppose to go in the controller name space
then how can you have a different User controller; the answer is you can't, it's
a name conflict cause by the namespace itself. In addition to that it bad OOP 
design. Almost never are the classes User and Controller_User equivalent. User 
might be a interface for Controller_User or a subclass, but it's no controller. 
Don't use namespaces as an extention of a class name, it will almost always 
create incorrect class names which will result in either confusing code, or the
need to have the namespace in the code for the code to make "sense". That's not
what namespaces are for, they should only be used to create a "name space".

Incidentally, the app namespace is actually a valid namespace, even though it 
doesn't follow the exact recomendation above, it does meet the requirements due
to how it works. It is the namespace of the alias of all top classes in the 
cascading file system, which gurantees it only has a single name for every class
and it is also guranteed to have unique name for the current execution. In 
addition there are no other classes in it other then the classes loaded, it is
more or less an empty namespace that autopopulates and can never load more then
one name. Thus, even though the names are not fixed to a file, it fulfills all 
three requirements of a namespace.

Note: Because each module has an unique namespace, and the reasoning above, a
call to \mynamespace\something\Some_Class where \mynamespace\something is 
registered in the modules for the system, will result in a require of the file
with out any file checks. This might seem wrong because it is not passing the 
class to the next registered autoloader, but it is actually correct. While the
class name might not be unique, the namespace is GURANTEED to be unique. If we 
fail to load the file there is no point to pass execution further when we know
no other autoloader will find it, but instead to send the error that the file
is suppose to be THERE, and it's not.

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