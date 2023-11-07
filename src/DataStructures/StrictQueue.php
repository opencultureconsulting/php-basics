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

use Countable;
use Iterator;
use Serializable;

/**
 * A (type-sensitive) destructive First In, First Out Queue.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
class StrictQueue extends AbstractStrictList
{
    /**
     * Get and remove the first item.
     * @see Iterator::current
     *
     * @return mixed The first item or NULL if empty
     */
    public function current(): mixed
    {
        return array_shift($this->items);
    }

    /**
     * Dequeue the first item.
     * Alias of Queue::current
     *
     * @return mixed The first item or NULL if empty
     */
    public function dequeue(): mixed
    {
        return $this->current();
    }

    /**
     * Enqueue items.
     * Alias of Queue::append
     *
     * @param mixed ...$items One or more items to add
     *
     * @return void
     */
    public function enqueue(mixed ...$items): void
    {
        $this->append(...$items);
    }

    /**
     * Get the first item without removing it.
     *
     * @return mixed The first item or NULL if empty
     */
    public function peek(): mixed
    {
        return $this->items[array_key_first($this->items)] ?? null;
    }
}
