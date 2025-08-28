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

namespace OCC\Basics\Interfaces;

use function is_null;

/**
 * A generic implementation of the ArrayAccess interface.
 *
 * Internally it accesses the protected `$_data` array.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Interfaces
 *
 * @api
 *
 * @template TKey of array-key
 * @template TValue of mixed
 *
 * @phpstan-require-implements \ArrayAccess<TKey, TValue>
 */
trait ArrayAccessTrait
{
    /**
     * Holds the array-accessible data.
     *
     * @var array<TKey, TValue>
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Check if the specified offset exists.
     *
     * @param TKey $offset The offset to check for
     *
     * @return bool Whether the offset exists
     *
     * @api
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Retrieve data at the specified offset.
     *
     * @param TKey $offset The offset to retrieve
     *
     * @return ?TValue The value at the offset or NULL if invalid
     *
     * @api
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->_data[$offset] ?? null;
    }

    /**
     * Assign a value to the specified offset.
     *
     * @param ?TKey $offset The offset to assign to or NULL to append
     * @param TValue $value The value to set
     *
     * @return void
     *
     * @api
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$offset] = $value;
        }
    }

    /**
     * Unset the specified offset.
     *
     * @param TKey $offset The offset to unset
     *
     * @return void
     *
     * @api
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->_data[$offset]);
    }
}
