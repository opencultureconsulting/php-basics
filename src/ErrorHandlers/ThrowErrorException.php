<?php

/**
 * Useful PHP Basics
 * Copyright (C) 2023 Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace OCC\Basics\ErrorHandlers;

use ErrorException;

/**
 * Throw internal errors as exceptions.
 *
 * Usage: set_error_handler(new ThrowErrorException());
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 */
class ThrowErrorException
{
    /**
     * Converts an internal PHP error into an ErrorException.
     *
     * @param int $severity The severity of the error
     * @param string $message The error message
     * @param ?string $file The name of the file the error was raised in
     * @param ?int $line The line number the error was raised in
     *
     * @return bool Always returns FALSE when not throwing an exception
     *
     * @throws ErrorException
     */
    public function __invoke(int $severity = E_USER_ERROR, string $message = '', ?string $file = null, ?int $line = null): bool
    {
        if ((error_reporting() & $severity) > 0) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }
        return false;
    }
}
