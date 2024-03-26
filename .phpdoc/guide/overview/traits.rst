.. title:: Traits

Traits
######

.. sidebar:: Table of Contents
  .. contents::

Getter
======

Reads data from inaccessible properties by using magic methods.

To make a `protected` or `private` property readable, provide a method named
`_magicGet{Property}()` which handles the reading. Replace `{Property}` in
the method's name with the name of the actual property (with an uppercase
first letter).

> Example: If the property is named `$fooBar`, the "magic" method has to be
> `_magicGetFooBar()`. This method is then called when `$fooBar` is read in
> a context where it normally would not be accessible.

OverloadingGetter
=================

Overloads a class with readable magic properties.

Internally it reads the protected `$_data` array whose keys are interpreted
as property names.

> Example: Reading `Foo->bar` will return the value of `$_data['bar']`.

OverloadingSetter
=================

Overloads a class with writable magic properties.

Internally it writes the protected `$_data` array whose keys are interpreted
as property names.

> Example: `Foo->bar = 42;` will set `$_data['bar']` to `42`.

Setter
======

Writes data to inaccessible properties by using magic methods.

To make a `protected` or `private` property writable, provide a method named
`_magicSet{Property}()` which handles the writing. Replace `{Property}` in
the method's name with the name of the actual property (with an uppercase
first letter).

> Example: If the property is named `$fooBar`, the "magic" method has to be
> `_magicSetFooBar()`. This method is then called when `$fooBar` is written
> to in a context where it normally would not be accessible.

Singleton
=========

Allows just a single instance of the class using this trait.

Get the singleton by calling the static method `getInstance()`.

If there is no object yet, the constructor is called with the same arguments
as `getInstance()`. Any later call will just return the already instantiated
object (irrespective of the given arguments).

In order for this to work as expected, the constructor has to be implemented
as `private` to prevent direct instantiation of the class.

TypeChecker
===========

A generic data type checker.

This allows to set a list of allowed atomic data types and fully qualified
class names. It also provides a method to check if a value's data type matches
at least one of these types.
