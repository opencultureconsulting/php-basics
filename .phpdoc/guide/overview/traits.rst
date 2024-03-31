.. title:: Traits

Traits
######

.. sidebar:: Table of Contents
  .. contents::

This package provides a number of generic traits like different getter and setter methods, an implementation of the
singleton design pattern and some little helpers. Those traits are too small to justify their own packages and most of them
are dependencies of the :doc:`datastructures` and :doc:`interfaces` anyway.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\Getter`

Getter
======

*Reads data from inaccessible properties by using magic methods.*

To make a `protected` or `private` property readable, provide a method named `_magicGet{Property}()` which handles the
reading. Replace `{Property}` in the method's name with the name of the actual property (with an uppercase first
letter).

Trying to access an undefined property or a property without corresponding "magic" getter method will result in an
`\InvalidArgumentException <https://www.php.net/invalidargumentexception>`_.

  Example: If the property is named `$fooBar`, the "magic" method has to be `_magicGetFooBar()`. This method is then
  called when `$fooBar` is read in a context where it normally would not be accessible.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\Setter`

Setter
======

*Writes data to inaccessible properties by using magic methods.*

To make a `protected` or `private` property writable, provide a method named `_magicSet{Property}()` which handles the
writing. Replace `{Property}` in the method's name with the name of the actual property (with an uppercase first
letter).

Trying to access an undefined property or a property without corresponding "magic" setter method will result in an
`\InvalidArgumentException <https://www.php.net/invalidargumentexception>`_.

  Example: If the property is named `$fooBar`, the "magic" method has to be `_magicSetFooBar()`. This method is then
  called when `$fooBar` is written to in a context where it normally would not be accessible.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\OverloadingGetter`

OverloadingGetter
=================

*Overloads a class with readable virtual properties.*

It reads a protected internal array whose keys are interpreted as property names.

Trying to access an undefined virtual property will not issue any warning or error, but return `NULL` instead.

  Example: Reading `Foo->bar` will return the value of `Foo::$_data['bar']`.

.. note::
  Internally it uses the `$_data` array, the same as some :doc:`datastructures` and all :doc:`interfaces` of this
  package.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\OverloadingSetter`

OverloadingSetter
=================

*Overloads a class with writable virtual properties.*

It writes a protected internal array whose keys are interpreted as property names.

Trying to access a previously undefined virtual property will create a new one with the given name.

  Example: `Foo->bar = 42;` will set `Foo::$_data['bar']` to `42`.

.. note::
  Internally it uses the `$_data` array, the same as some :doc:`datastructures` and all :doc:`interfaces` of this
  package.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\Singleton`

Singleton
=========

*Allows just a single instance of the class using this trait.*

Get the singleton by calling the static method `getInstance()`. If there is no object yet, the constructor is called
with the same arguments as `getInstance()`. Any later call will just return the already instantiated object
(irrespective of the given arguments).

.. caution::
  In order for this to work as expected, the constructor has to be implemented as `private` to prevent direct
  instantiation of the class.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Traits\TypeChecker`

TypeChecker
===========

*A generic data type checker.*

This allows to set a list of allowed atomic data types and fully qualified class names. It also provides a method to
check if a value's data type matches at least one of these types.

Available atomic types are `array`, `bool`, `callable`, `countable`, `float` / `double`, `int` / `integer` / `long`,
`iterable`, `null`, `numeric`, `object`, `resource`, `scalar` and `string`.
