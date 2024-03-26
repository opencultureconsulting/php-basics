.. title:: Datastructures

Typed Datastructures
####################

.. sidebar:: Table of Contents
  .. contents::

All datastructures in this package have in common that you can control the types of items they can hold.

To restrict allowed data types for items, provide the constructor with an array of atomic types or fully qualified
class names you want to allow as item types. Available atomic types are `array`, `bool`, `callable`, `countable`,
`float` / `double`, `int` / `integer` / `long`, `iterable`, `null`, `numeric`, `object`, `resource`, `scalar` and
`string`.

Trying to add an item with a data type not on the list of allowed types to a strict datastructure will result in an
:php:class:`OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException`.

  Examples:

  .. code-block:: php
    // create a collection of strings
    $stringCollection = new StrictCollection(['string']);

    // create a queue of PSR-15 middlewares
    $middlewareQueue = new StrictQueue(['Psr\Http\Server\MiddlewareInterface']);

StrictCollection
================

.. sidebar:: API Documentation
  :php:class:`OCC\Basics\DataStructures\StrictCollection`

*A type-sensitive, unsorted collection of items.*

Holds items as key/value pairs where keys identify the items and have to be valid array keys while values can be of any
controlled type.

A `StrictCollection` can be accessed like an array, but not traversed because it has no particular order. Technically
speaking, `StrictCollection` implements `\ArrayAccess <https://www.php.net/arrayaccess>`_, `\Countable
<https://www.php.net/countable>`_ and `\Serializable <https://www.php.net/serializable>`_, but no `\Traversable
<https://www.php.net/traversable>`_ interface.

.. note::
  Internally it holds the items in the `$_data` array, the same as most :php:namespace:`OCC\Basics\Interfaces` and
  :php:namespace:`OCC\Basics\Traits` of this package.

StrictList
==========

.. sidebar:: API Documentation
  :php:class:`OCC\Basics\DataStructures\StrictList`

*A type-sensitive, taversable list of items.*

Extends `\SplDoublyLinkedList <https://www.php.net/spldoublylinkedlist>`_ with an option to restrict the allowed data
types for list items.

StrictQueue
===========

.. sidebar:: API Documentation
  :php:class:`OCC\Basics\DataStructures\StrictQueue`

*A type-sensitive, taversable queue (FIFO) of items.*

Extends `\SplDoublyLinkedList <https://www.php.net/spldoublylinkedlist>`_ with an option to restrict the allowed data
types for list items.

StrictStack
===========

.. sidebar:: API Documentation
  :php:class:`OCC\Basics\DataStructures\StrictStack`

*A type-sensitive, taversable stack (LIFO) of items.*

Extends `\SplDoublyLinkedList <https://www.php.net/spldoublylinkedlist>`_ with an option to restrict the allowed data
types for list items.
