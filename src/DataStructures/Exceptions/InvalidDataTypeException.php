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

namespace OCC\Basics\DataStructures\Exceptions;

use DomainException;

use function get_debug_type;
use function sprintf;

/**
 * Common exception for type-sensitive datastructures.
 *
 * Exception thrown if a value does not adhere to the defined list of valid
 * data types.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 */
final class InvalidDataTypeException extends DomainException
{
    /**
     * Create an exception for a parameter whose value is not of allowed type.
     *
     * @param int $position The parameter's position (starting at `1`)
     * @param mixed $value The parameter's value
     *
     * @return self The exception
     *
     * @internal
     */
    public static function forParameter(int $position, mixed $value): self
    {
        return new self(
            sprintf(
                'Parameter %d must be an allowed type, %s given.',
                $position,
                get_debug_type($value)
            )
        );
    }
}
