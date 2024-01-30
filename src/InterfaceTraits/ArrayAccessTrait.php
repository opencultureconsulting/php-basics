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

namespace OCC\Basics\InterfaceTraits;

use ArrayAccess;

/**
 * A generic implementation of the ArrayAccess interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\InterfaceTraits
 *
 * @api
 *
 * @template TValue of mixed
 * @implements ArrayAccess<array-key, TValue>
 * @phpstan-require-implements ArrayAccess
 */
trait ArrayAccessTrait
{
    /**
     * Holds the array-accessible data.
     *
     * @var array<TValue>
     */
    protected array $data = [];

    /**
     * Check if the specified offset exists.
     *
     * @param array-key $offset The offset to check for
     *
     * @return bool Whether the offset exists
     *
     * @internal
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Retrieve data at the specified offset.
     *
     * @param array-key $offset The offset to retrieve
     *
     * @return ?TValue The value at the offset or NULL if invalid
     *
     * @internal
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Assign a value to the specified offset.
     *
     * @param ?array-key $offset The offset to assign to or NULL to append
     * @param TValue $value The value to set
     *
     * @return void
     *
     * @internal
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
     *
     * @param array-key $offset The offset to unset
     *
     * @return void
     *
     * @internal
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}
