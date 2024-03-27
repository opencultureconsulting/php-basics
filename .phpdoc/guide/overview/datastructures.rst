.. title:: Datastructures

Typed Datastructures
####################

.. sidebar:: Table of Contents
  .. contents::

The datastructures in this package are derived from their respective `Standard PHP Library (SPL) counterparts
<https://www.php.net/spl.datastructures>`_ which they extend by the option to control the data types of items those
datastructures can hold.

To restrict allowed data types for items, provide the constructor with an array of atomic types or fully qualified
class names you want to allow as item types. Available atomic types are `array`, `bool`, `callable`, `countable`,
`float` / `double`, `int` / `integer` / `long`, `iterable`, `null`, `numeric`, `object`, `resource`, `scalar` and
`string`.

Trying to add an item with a data type not on the list of allowed types to a strict datastructure will result in an
:php:class:`OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException`.

All strict datastructures inherit the implementation of the `\ArrayAccess <https://www.php.net/arrayaccess>`_,
`\Countable <https://www.php.net/countable>`_ and `\Serializable <https://www.php.net/serializable>`_ interfaces. All
but `StrictCollection` also implement the `\Traversable <https://www.php.net/traversable>`_ interface.

  Examples:

  .. code-block:: php
    // create a collection of strings
    $stringCollection = new StrictCollection(['string']);

    // create a queue of PSR-15 middlewares
    $middlewareQueue = new StrictQueue(['Psr\Http\Server\MiddlewareInterface']);

StrictCollection
================

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\DataStructures\StrictCollection`

*A type-sensitive, unsorted collection of items.*

Holds items as key/value pairs where keys identify the items and have to be valid array keys while values can be of any
controlled type. The collection can be accessed like an array, but not traversed because it has no particular order.

.. note::
  Internally it holds the items in the `$_data` array, the same as most :php:namespace:`OCC\Basics\Interfaces` and
  :php:namespace:`OCC\Basics\Traits` of this package.

StrictArray
================

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\DataStructures\StrictArray`

*A type-sensitive, traversable array of items.*

Holds items as key/value pairs where keys identify the items and have to be valid array keys while values can be of any
controlled type. The array can be accessed and traversed just like any other array.

.. note::
  Internally it holds the items in the `$_data` array, the same as most :php:namespace:`OCC\Basics\Interfaces` and
  :php:namespace:`OCC\Basics\Traits` of this package.

StrictList
==========

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\DataStructures\StrictList`

*A type-sensitive, taversable list of items.*

Extends `\SplDoublyLinkedList <https://www.php.net/spldoublylinkedlist>`_ with an option to restrict the allowed data
types for list items. The list can be accessed and traversed like an array, but has only consecutive numerical keys.

StrictQueue
===========

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\DataStructures\StrictQueue`

*A type-sensitive, taversable queue (FIFO) of items.*

Extends `\SplQueue <https://www.php.net/splqueue>`_ with an option to restrict the allowed data types for queue items.
The queue can be accessed and traversed like an array, but has only consecutive numerical keys. Traversal follows the
first-in, first-out (FIFO) principle meaning that items are returned in the same order they were added to the queue.

It is recommended to use the `StrictQueue::enqueue()` and `StrictQueue::dequeue()` alias methods when working with a
queue, because those will ensure proper FIFO behavior and remove items while traversing.

StrictStack
===========

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\DataStructures\StrictStack`

*A type-sensitive, taversable stack (LIFO) of items.*

Extends `\SplStack <https://www.php.net/splstack>`_ with an option to restrict the allowed data types for stack items.
The stack can be accessed and traversed like an array, but has only consecutive numerical keys. Traversal follows the
last-in, first-out (LIFO) principle meaning that items are returned in the reversed order they were added to the stack.

It is recommended to use the `StrictStack::stack()` and `StrictStack::unstack()` alias methods when working with a
stack, because those will ensure proper LIFO behavior and remove items while traversing.
