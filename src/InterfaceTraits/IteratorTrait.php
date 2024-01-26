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

use Iterator;

/**
 * A generic implementation of the Iterator interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\InterfaceTraits
 *
 * @template TKey of int|string
 * @template TValue of mixed
 * @template TData of array<TKey, TValue>
 * @implements Iterator<TKey, TValue>
 * @phpstan-require-implements Iterator
 */
trait IteratorTrait
{
    /**
     * Holds the iterable data.
     *
     * @var TData
     */
    protected array $data = [];

    /**
     * Return the current item.
     *
     * @return TValue|false The current item or FALSE if invalid
     */
    public function current(): mixed
    {
        return current($this->data);
    }

    /**
     * Return the current key.
     *
     * @return ?TKey The current key or NULL if invalid
     */
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * Move forward to next item.
     *
     * @return void
     */
    public function next(): void
    {
        next($this->data);
    }

    /**
     * Move back to previous item.
     *
     * @return void
     */
    public function prev(): void
    {
        prev($this->data);
    }

    /**
     * Rewind the iterator to the first item.
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->data);
    }

    /**
     * Check if current position is valid.
     *
     * @return bool Whether the current position is valid
     */
    public function valid(): bool
    {
        return !is_null($this->key());
    }
}
