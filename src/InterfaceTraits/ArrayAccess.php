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

namespace OCC\Basics\InterfaceTraits;

/**
 * A generic implementation of the ArrayAccess interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 *
 * @phpstan-require-implements \ArrayAccess
 */
trait ArrayAccess
{
    /**
     * Holds the array-accessible data.
     */
    private array $data = [];

    /**
     * Whether the specified offset exists.
     * @see \ArrayAccess::offsetExists()
     *
     * @param mixed $offset The offset to check for
     *
     * @return bool Whether the offset exists
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Retrieve data at the specified offset.
     * @see \ArrayAccess::offsetGet()
     *
     * @param mixed $offset The offset to retrieve at
     *
     * @return mixed The value at the offset or NULL
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Assign a value to the specified offset.
     * @see \ArrayAccess::offsetSet()
     *
     * @param mixed $offset The offset to assign to or NULL to append
     * @param mixed $value The value to set
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Unset the specified offset.
     * @see \ArrayAccess::offsetUnset()
     *
     * @param mixed $offset The offset to unset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}
