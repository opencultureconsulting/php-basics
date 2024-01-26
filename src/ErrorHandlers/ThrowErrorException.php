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

use ErrorException;

/**
 * Throw internal errors as exceptions.
 *
 * Usage: set_error_handler(new ThrowErrorException());
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\ErrorHandlers
 *
 * @api
 */
class ThrowErrorException
{
    /**
     * Convert an internal PHP error into an ErrorException.
     *
     * @param int $errno The severity of the error
     * @param string $errstr The error message
     * @param ?string $errfile The name of the file the error was raised in
     * @param ?int $errline The line number the error was raised in
     *
     * @return bool Always returns FALSE when not throwing an exception
     *
     * @throws ErrorException
     */
    public function __invoke(
        int $errno = E_USER_ERROR,
        string $errstr = '',
        ?string $errfile = null,
        ?int $errline = null
    ): bool {
        if ((error_reporting() & $errno) > 0) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        return false;
    }
}
