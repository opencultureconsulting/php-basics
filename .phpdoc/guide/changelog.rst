.. title:: Changelog

Changelog
#########

.. sidebar:: Table of Contents
  .. contents::

Unreleased
==========

**Breaking Changes:**

* Raised minimum PHP version from 8.1 to 8.2, as PHP 8.1 reached end of life

**Maintenance:**

* Updated Psalm to version 7 and PHP Mess Detector to version 3
* Restricted development tools to patch-level updates (e.g. `~2.3.7`), so new minor versions arrive via Dependabot
* Switched CI workflows to `shivammathur/setup-php` and run all tools from `vendor/bin`
* Imported the `Override` and `ReturnTypeWillChange` attributes instead of using their fully qualified names

**Bug Fixes:**

* `TypeChecker` no longer calls arbitrary `is_*()` functions for class names (e.g. a class `File` caused a `TypeError`
  from `is_file()`, and `Countable` accepted arrays via `is_countable()`). Atomic type keywords are now matched exactly
  and must be lowercase; everything else is checked with `instanceof`
* `StrictArray` now supports appending with `$array[] = $value`
* `StrictCollection::get()` and `StrictCollection::remove()` now work for items with value `null`
* `TriggerExceptionError` no longer uses `E_USER_ERROR` (deprecated since PHP 8.4), but triggers an `E_USER_WARNING`
  and exits with status `255`

v2.1.3
======

**Maintenance:**

* Updated development dependencies
* Raised PHPStan rule level to 10
* Updated links to `documentation <https://code.opencultureconsulting.com/php-basics/>`_
* Used more generics in PHPStan and Psalm type-hints

v2.1.2
======

**New Features:**

* Added Composer commands for development tools (PHP Mess Detector, Psalm Taint Analysis)

v2.1.1
======

**New Features:**

* Added Composer commands for development tools (PHP_CodeSniffer, PHP-CS-Fixer, PHPStan, Psalm and phpDocumentor)

**Minor Changes:**

* Extended `documentation <https://code.opencultureconsulting.com/php-basics/>`_

v2.1.0
======

**Breaking Changes:**

* :php:namespace:`OCC\Basics\DataStructures\Traits` for datastructures renamed to
  :php:trait:`OCC\Basics\DataStructures\Traits\StrictSplDoublyLinkedListTrait`

**New Features:**

* Extended API for :php:class:`OCC\Basics\DataStructures\StrictArray`

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

* Prefixed internal methods for :php:trait:`OCC\Basics\Traits\Getter` and :php:trait:`OCC\Basics\Traits\Setter` with
  `_` to avoid confusion with regular class methods

  .. code-block:: php
    // old methods
    function magicGet{Property}(): mixed
    function magicSet{Property}(mixed $value): void

  .. code-block:: php
    // new methods
    function _magicGet{Property}(): mixed
    function _magicSet{Property}(mixed $value): void

**New Features:**

* Added new datastructure :php:class:`OCC\Basics\DataStructures\StrictCollection`
* Added new datastructure :php:class:`OCC\Basics\DataStructures\StrictArray`
* Added new error handler :php:class:`OCC\Basics\ErrorHandlers\TriggerExceptionError`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingGetter`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingSetter`
* Added new trait :php:trait:`OCC\Basics\Traits\TypeChecker`
* Extended API for all datastructures (see :php:trait:`OCC\Basics\DataStructures\Traits\StrictSplDoublyLinkedListTrait`)
* Introduced :php:class:`OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException` for strict datastructures

**Minor Changes:**

* Extended `documentation <https://code.opencultureconsulting.com/php-basics/>`_

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

**Minor Changes:**

* Improved exception handling in :php:trait:`Singleton <OCC\Basics\Traits\Singleton>` trait

v1.0.0
======

**Initial Release**
