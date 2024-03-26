.. title:: Changelog

Changelog
#########

.. sidebar:: Table of Contents
  .. contents::

v2.0.0
======

**Breaking Changes:**

* Raised minimum PHP version from 8.0 to 8.1 in order to use `new features <https://www.php.net/releases/8.1/>`_ like
  `array_is_list() <https://www.php.net/array_is_list>`_ and the spread operator on string-keyed arrays
* :php:namespace:`OCC\Basics\Interfaces` traits renamed and moved to different namespace

  .. code-block::
    OCC\Basics\InterfaceTraits\ArrayAccess       -> OCC\Basics\Interfaces\ArrayAccessTrait
    OCC\Basics\InterfaceTraits\Countable         -> OCC\Basics\Interfaces\CountableTrait
    OCC\Basics\InterfaceTraits\IteratorAggregate -> OCC\Basics\Interfaces\IteratorAggregateTrait
    OCC\Basics\InterfaceTraits\Iterator          -> OCC\Basics\Interfaces\IteratorTrait

* Renamed internal methods for :php:trait:`OCC\Basics\Traits\Getter` and :php:trait:`OCC\Basics\Traits\Setter` to avoid
  confusion with regular class methods

  .. code-block:: php
    // old methods
    function magicGet{PascalCasePropertyName}(): mixed
    function magicSet{PascalCasePropertyName}(mixed $value): void

  .. code-block:: php
    // new methods
    function _magicGet{PascalCasePropertyName}(): mixed
    function _magicSet{PascalCasePropertyName}(mixed $value): void

**New Features:**

* Added new datastructure :php:class:`OCC\Basics\DataStructures\StrictCollection`
* Added new error handler :php:class:`OCC\Basics\ErrorHandlers\TriggerExceptionError`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingGetter`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingSetter`
* Added new trait :php:trait:`OCC\Basics\Traits\TypeChecker`
* Extended API for all datastructures (see :php:trait:`OCC\Basics\DataStructures\Traits\StrictSplDatastructureTrait`)
* Introduced :php:class:`OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException` for strict datastructures
* Extended `documentation <https://opencultureconsulting.github.io/php-basics/>`_

v1.1.0
======

**Breaking Changes:**

* Changed the constructor's signature for all :php:namespace:`OCC\Basics\DataStructures` to improve compatibility with
  the corresponding `SPL datastructures <https://www.php.net/spl.datastructures>`_

  .. code-block:: php
    // old constructor signature
    public function __construct(iterable $items = [], array $allowedTypes = [])

  .. code-block:: php
    // new constructor signature
    public function __construct(array $allowedTypes = [])

v1.0.1
======

**New Features:**

* Improved exception handling in :php:trait:`Singleton <OCC\Basics\Traits\Singleton>` trait

v1.0.0
======

**Initial Release**
