.. title:: Interfaces

Interface Traits
################

.. sidebar:: Table of Contents
  .. contents::

This package contains some traits implementing common interfaces, which can easily be used in any class that internally
uses an array for holding its properties or data. They also share the same internal logic to allow combining multiple
traits within the same class.

.. note::
  Internally all interface traits use the `$_data` array, the same as some :doc:`datastructures` and :doc:`traits` of
  this package.

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Interfaces\ArrayAccessTrait`

ArrayAccessTrait
================

*A generic implementation of the ArrayAccess interface.*

The `\ArrayAccess <https://www.php.net/arrayaccess>`_ interface allows objects to be accessed like arrays.

  Usage:

  .. code-block:: php
    class Foo implements ArrayAccess
    {
        use \OCC\Basics\Interfaces\ArrayAccessTrait;
    }

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Interfaces\CountableTrait`

CountableTrait
==============

*A generic implementation of the Countable interface.*

The `\Countable <https://www.php.net/countable>`_ interface allows objects to be used with the `count()
<https://www.php.net/count>`_ function.

  Usage:

  .. code-block:: php
    class Foo implements Countable
    {
        use \OCC\Basics\Interfaces\CountableTrait;
    }

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Interfaces\IteratorAggregateTrait`

IteratorAggregateTrait
======================

*A generic implementation of the IteratorAggregate interface.*

The `\IteratorAggregate <https://www.php.net/iteratoraggregate>`_ interface creates an external `\ArrayIterator
<https://www.php.net/arrayiterator>`_ for traversing the object's internal data array.

  Usage:

  .. code-block:: php
    class Foo implements IteratorAggregate
    {
        use \OCC\Basics\Interfaces\IteratorAggregateTrait;
    }

.. sidebar:: API Documentation
  * :php:trait:`OCC\Basics\Interfaces\IteratorTrait`

IteratorTrait
=============

*A generic implementation of the Iterator interface.*

The `\Iterator <https://www.php.net/iterator>`_ interface creates an internal iterator for traversing the object's data
array.

  Usage:

  .. code-block:: php
    class Foo implements Iterator
    {
        use \OCC\Basics\Interfaces\IteratorTrait;
    }
