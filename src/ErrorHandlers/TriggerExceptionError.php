<?php

/**
 * PHP Basics
 *
 * Copyright (C) 2024 Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace OCC\Basics\ErrorHandlers;

use Throwable;

/**
 * Trigger errors for uncaught exceptions.
 *
 * If registered as exception handler, this catches an uncaught exception and
 * convert it into an internal PHP error of severity `E_USER_ERROR`.
 *
 * > Usage: `set_exception_handler(new TriggerExceptionError());`
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\ErrorHandlers
 */
class TriggerExceptionError
{
    /**
     * Convert an uncaught exception into an PHP error.
     *
     * @param Throwable $exception The exception
     *
     * @return void
     */
    public function __invoke(Throwable $exception): void
    {
        $message = sprintf(
            'Uncaught Exception [%d] in file %s on line %d: %s',
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage()
        );
        trigger_error($message, E_USER_ERROR);
    }
}
