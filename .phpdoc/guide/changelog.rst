.. title:: Changelog

Changelog
#########

* :ref:`v2-0-0`
* :ref:`v1-1-0`
* :ref:`v1-0-1`
* :ref:`v1-0-0`

.. _v2-0-0:

v2.0.0
======

**Breaking Changes:**

* Raised minimum PHP version from 8.0 to 8.1
* Renamed traits for :php:namespace:`OCC\Basics\Interfaces` and moved to different namespace
* Renamed internal methods for :php:trait:`OCC\Basics\Traits\Getter` and :php:trait:`OCC\Basics\Traits\Setter` to avoid
  confusion with regular class method

  .. code-block:: php
    // old methods
    protected function magicGet{PascalCasePropertyName}(): mixed
    protected function magicSet{PascalCasePropertyName}(mixed $value): void

  .. code-block:: php
    // new methods
    protected function _magicGet{PascalCasePropertyName}(): mixed
    protected function _magicSet{PascalCasePropertyName}(mixed $value): void

**New Features:**

* Added new datastructure :php:class:`OCC\Basics\DataStructures\StrictCollection`
* Added new error handler :php:class:`OCC\Basics\ErrorHandlers\TriggerExceptionError`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingGetter`
* Added new trait :php:trait:`OCC\Basics\Traits\OverloadingSetter`
* Extended API for all datastructures
* Extended `documentation <https://opencultureconsulting.github.io/basics/>`_

.. _v1-1-0:

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

.. _v1-0-1:

v1.0.1
======

**New Features:**

* Improved exception handling in :php:trait:`Singleton <OCC\Basics\Traits\Singleton>` trait

.. _v1-0-0:

v1.0.0
======

**Initial Release**
