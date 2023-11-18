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
 * A generic implementation of the Iterator interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 *
 * @implements \Iterator
 */
trait Iterator /* implements \Iterator */
{
    /**
     * Holds the iterable data.
     */
    private array $data = [];

    /**
     * Return the current item.
     * @see \Iterator::current()
     *
     * @return mixed The current item or FALSE if invalid
     */
    public function current(): mixed
    {
        return current($this->data);
    }

    /**
     * Return the current key.
     * @see \Iterator::key()
     *
     * @return mixed The current key or NULL if invalid
     */
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * Move forward to next item.
     * @see \Iterator::next()
     *
     * @return void
     */
    public function next(): void
    {
        next($this->data);
    }

    /**
     * Rewind the iterator to the first item.
     * @see \Iterator::rewind()
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->data);
    }

    /**
     * Checks if current position is valid.
     * @see \Iterator::valid()
     *
     * @return bool Whether the current position is valid
     */
    public function valid(): bool
    {
        return !is_null($this->key());
    }
}
