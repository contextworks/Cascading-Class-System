Cascading Class System Standard
==========================================

A Cascading Class System (CCS) is one part of a Cascading File System (CFS). In 
CCS the same class MAY exist in any number of modules but only the top class is
ever loaded. eg. If Alice has a class A and class B in her module, and class A
has an internal dependency to class B, then normally it is very dificult for Bob
to change Alice's class A. But in a CCS Bob can change class B by adopting it in
his module and Alice's class A will then use Bob's class B. This is very useful
when Alice's module is something like a framework, as it allows Bob to have full
control and customize it to his's needs.

A CCS can have any number of modules in a stack and any module may overwrite 
only the modules beneth it.

Standard Definition, Edition 1
===================

1. The \app namespace is reserved and known as "the project namespace".

2. Each module defines it's code in it's own UNIQUE namespace (eg. \foo\core, 
\bar\database, etc), which is mapped to the module path (there is no 
relationship between the path and the namespace); each module must map to an 
unique namespace (exception, the \app namespace may be mapped to any number of 
modules). Modules MAY choose to map to the \app namespace directly if they are 
SPECIFIC to the project (ie. non-reusable, final, or not intended as reusable), 
thus allowing for easier use by avoiding redundant namespace notation.

3. The class name MUST map to a directory or file inside the /classes/ directory 
of each module. If EXT is defined it will be used as the mapped file's 
extention. eg. if EXT is defined as ".php" the class Foo_Bar_Foobar maps to the 
classes/Foo/Bar/Foobar.php file, if EXT is not defined it should map to 
classes/Foo/Bar/Foobar.

4. When a class is loaded:

    a) if the namespace is \app then the class is in the project namespace and 
    the autoloader MUST look into the CFS for the first file matching the 
    Class_Name pattern, load it and if the module from which it was loaded was 
    not mapped to the \app namespace define a class_alias for it from the 
    module's namespace to the \app namespace.

    b) if the namespace of the requested class is not \app then the class is 
    simply loaded directly from the module matching the namespace.

5. All logic MUST BE designed to work as if the \app namespace is the ONLY 
namespace that exists. Classes should never rely on any other namespace, not 
even their own namespace. All modules that are not under the \app namespace 
MUST ALWAYS use the \app version of ANY other class they call. If the class 
is making use of a static method within itself it should ALWAYS use the static 
keyword for methods with public and protected access and the self keyword for 
members with private access, and NEVER the class name; thus allow for extension 
of the class to a different name with out breaking it.