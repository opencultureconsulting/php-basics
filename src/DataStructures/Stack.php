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

namespace OCC\Basics\DataStructures;

/**
 * A type-sensitive, destructive Last In, First Out Stack.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
class Stack extends AbstractList
{
    /**
     * Get the last item and remove it.
     * @see Iterator::current
     *
     * @return mixed The last item or NULL if empty
     */
    public function current(): mixed
    {
        return array_pop($this->items);
    }

    /**
     * Get a single item without removing it.
     *
     * @param ?int $offset Optional offset to peek, defaults to last
     *
     * @return mixed The item or NULL if empty
     */
    public function peek(?int $offset = null): mixed
    {
        if (is_null($offset)) {
            return end($this->items) ?? null;
        }
        $item = array_slice($this->items, $offset, 1);
        return $item[0] ?? null;
    }

    /**
     * Check if there is an item left on the stack.
     * @see Iterator::valid
     *
     * @return bool Is there an item on the stack?
     */
    public function valid(): bool
    {
        return (bool) $this->count();
    }
}
