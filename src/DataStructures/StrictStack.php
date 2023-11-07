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
 * A (type-sensitive) destructive Last In, First Out Stack.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
class StrictStack extends AbstractStrictList
{
    /**
     * Get and remove the last item.
     * @see Iterator::current
     *
     * @return mixed The last item or NULL if empty
     */
    public function current(): mixed
    {
        return array_pop($this->items);
    }

    /**
     * Get the last item without removing it.
     *
     * @return mixed The first item or NULL if empty
     */
    public function peek(): mixed
    {
        return $this->items[array_key_last($this->items)] ?? null;
    }

    /**
     * Stack items.
     * Alias of Stack::append
     *
     * @param mixed ...$items One or more items to add
     *
     * @return void
     */
    public function stack(mixed ...$items): void
    {
        $this->append(...$items);
    }

    /**
     * Unstack the last item.
     * Alias of Stack::current
     *
     * @return mixed The last item or NULL if empty
     */
    public function unstack(): mixed
    {
        return $this->current();
    }
}
