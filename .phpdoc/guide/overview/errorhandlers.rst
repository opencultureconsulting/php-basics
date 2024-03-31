.. title:: Error Handlers

Error and Exception Handlers
############################

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\ErrorHandlers\ThrowErrorException`

ThrowErrorException
===================

*Throws internal errors as exceptions.*

If registered as error handler, this handles an internal PHP error by converting it into an `\ErrorException
<https://www.php.net/errorexception>`_. It respects the `error_reporting <https://www.php.net/error_reporting>`_
directive by only throwing an exception if the severity of the internal error is the same or higher than the setting.

  Usage:

  .. code-block:: php
    set_error_handler(new ThrowErrorException());

.. caution::
  By design user-defined error handlers can't handle errors of severity `E_ERROR`, `E_PARSE`, `E_CORE_ERROR`,
  `E_CORE_WARNING`, `E_COMPILE_ERROR`, `E_COMPILE_WARNING` and most of `E_STRICT`.

.. sidebar:: API Documentation
  * :php:class:`OCC\Basics\ErrorHandlers\TriggerExceptionError`

TriggerExceptionError
=====================

*Triggers errors for uncaught exceptions.*

If registered as exception handler, this catches an uncaught exception and converts it into an internal PHP error of
severity `E_USER_ERROR`.

  Usage:

  .. code-block:: php
    set_exception_handler(new TriggerExceptionError());
