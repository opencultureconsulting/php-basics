.. title:: Error Handlers

Error and Exception Handlers
############################

.. sidebar:: Table of Contents
  .. contents::

ThrowErrorException
===================

Throws internal errors as exceptions.

If registered as error handler, this converts an internal PHP error into an
`ErrorException`. It respects the `error_reporting` directive.

> Usage: `set_error_handler(new ThrowErrorException());`

TriggerExceptionError
=====================

Triggers errors for uncaught exceptions.

If registered as exception handler, this catches an uncaught exception and
converts it into an internal PHP error of severity `E_USER_ERROR`.

> Usage: `set_exception_handler(new TriggerExceptionError());`
