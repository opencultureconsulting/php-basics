<?php

/**
 * Script for testing TriggerExceptionError in a separate PHP process.
 */

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

set_exception_handler(new OCC\Basics\ErrorHandlers\TriggerExceptionError());

throw new RuntimeException('Something went wrong', 42);
