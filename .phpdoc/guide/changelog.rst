.. title:: Changelog

Changelog
#########

.. sidebar:: Table of Contents
  .. contents::

v3.0.1
======

**Bug Fixes:**

* Removed `@psalm-consistent-templates` from `StrictCollection`, `StrictList`, `StrictQueue` and `StrictStack`, because
  it made Psalm reject subclasses with a fixed item type (e.g. `class MyQueue extends StrictQueue<Foo>`)

v3.0.0
======

**New Features:**

* Added a PHPUnit test suite (`composer phpunit:test`), running on PHP 8.2 to 8.5 in CI
* Added static `fromArray()` constructors to all datastructures (e.g. `StrictList::fromArray([1, 2], ['int'])`),
  which also let PHPStan and Psalm infer the item types
* `TypeChecker` (and thus all datastructures) now supports union types (e.g. `int|string`), nullable types (e.g. `?int`)
  and `mixed`

**Breaking Changes:**

* Raised minimum PHP version from 8.1 to 8.2, as PHP 8.1 reached end of life
* Atomic type keywords for `TypeChecker` (and thus all datastructures) are now case-sensitive and must be lowercase
  (e.g. `'Int'` or `'STRING'` are now treated as class names and therefore never match scalar values)
* `StrictCollection` and `StrictArray` no longer implement the legacy `\Serializable` interface (and thus no longer
  have `serialize()` and `unserialize()` methods). Serializing them with PHP's `serialize()` and `unserialize()`
  functions works as before. `StrictList`, `StrictQueue` and `StrictStack` still implement it, because their SPL parent
  classes do
* `Getter::__isset()` no longer swallows exceptions thrown by `_magicGet*()` methods
* `TypeChecker::setAllowedTypes()` splits union and nullable types into separate types and throws an
  `\InvalidArgumentException` for empty types (e.g. `''` or `'int|'`)

**Maintenance:**

* Updated Psalm to version 7 and PHP Mess Detector to version 3
* Imported the `Override` and `ReturnTypeWillChange` attributes instead of using their fully qualified names
* The documentation is now built and deployed by GitHub Actions on every new release instead of being committed to
  the repository

**Bug Fixes:**

* `TypeChecker` no longer calls arbitrary `is_*()` functions for class names (e.g. a class `File` caused a `TypeError`
  from `is_file()`, and `Countable` accepted arrays via `is_countable()`). Everything but the atomic type keywords is
  now checked with `instanceof`
* `StrictArray` now supports appending with `$array[] = $value`
* `StrictCollection::get()` and `StrictCollection::remove()` now work for items with value `null`
* `TriggerExceptionError` no longer uses `E_USER_ERROR` (deprecated since PHP 8.4), but triggers an `E_USER_WARNING`
  and exits with status `255`
* `StrictList::add()`, `StrictQueue::add()` and `StrictStack::add()` now insert the item at the given offset instead of
  replacing the existing item
* `StrictList::prepend()`, `StrictQueue::prepend()` and `StrictStack::prepend()` now keep the order of the given items
  (like `array_unshift()`) instead of reversing them
* Unserializing a `StrictStack` (or any list in LIFO iteration mode) no longer reverses the order of its items

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
