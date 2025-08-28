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

namespace OCC\Basics\DataStructures;

use ArrayAccess;
use Countable;
use Iterator;
use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\DataStructures\Traits\StrictSplDoublyLinkedListTrait;
use RuntimeException;
use Serializable;
use SplQueue;
use Traversable;

/**
 * A type-sensitive, taversable queue (FIFO).
 *
 * Extends [\SplQueue](https://www.php.net/splqueue) with an option to restrict
 * the allowed data types for list items by providing the constructor with an
 * array of atomic types or fully qualified class names.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template AllowedType of mixed
 *
 * @extends SplQueue<AllowedType>
 * @implements ArrayAccess<int, AllowedType>
 * @implements Iterator<int, AllowedType>
 * @implements Traversable<int, AllowedType>
 */
class StrictQueue extends SplQueue implements ArrayAccess, Countable, Iterator, Serializable, Traversable
{
    /** @use StrictSplDoublyLinkedListTrait<AllowedType> */
    use StrictSplDoublyLinkedListTrait;

    /**
     * Dequeue an item from the queue.
     *
     * @return AllowedType The dequeued item
     *
     * @throws RuntimeException if the queue is empty
     *
     * @api
     */
    #[\Override]
    public function dequeue(): mixed
    {
        return $this->shift();
    }

    /**
     * Add an item to the queue.
     *
     * @param AllowedType $value The item to enqueue
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    #[\Override]
    public function enqueue(mixed $value): void
    {
        $this->push($value);
    }
}
