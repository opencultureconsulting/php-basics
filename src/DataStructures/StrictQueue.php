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

use RuntimeException;

/**
 * A type-sensitive, taversable First In, First Out Queue (FIFO).
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 *
 * @template AllowedTypes
 * @extends StrictList<AllowedTypes>
 */
class StrictQueue extends StrictList
{
    /**
     * Dequeue an item from the queue.
     * @see \SplQueue::dequeue()
     *
     * @return AllowedTypes The dequeued item
     */
    public function dequeue(): mixed
    {
        return parent::shift();
    }

    /**
     * Add an item to the queue.
     * @see \SplQueue::enqueue()
     *
     * @param AllowedTypes $item The item to enqueue
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function enqueue(mixed $item): void
    {
        parent::push($item);
    }

    /**
     * Set the mode of iteration.
     * @see \SplDoublyLinkedList::setIteratorMode()
     *
     * @param int $mode The new iterator mode (0 or 1)
     *
     * @return int The set of flags and modes of iteration
     *
     * @throws RuntimeException
     */
    final public function setIteratorMode(int $mode): int
    {
        if ($mode > 1) {
            throw new RuntimeException(
                sprintf(
                    'Changing the iterator direction of %s is prohibited.',
                    static::class
                )
            );
        }
        return parent::setIteratorMode($mode);
    }

    /**
     * Create a type-sensitive, traversable queue of items.
     *
     * @param iterable<AllowedTypes> $items Initial set of items
     * @param string[] $allowedTypes Allowed types of items (optional)
     */
    public function __construct(iterable $items = [], array $allowedTypes = [])
    {
        parent::__construct($items, $allowedTypes);
        $this->setIteratorMode(0);
    }
}
