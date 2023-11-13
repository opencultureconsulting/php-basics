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
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
class StrictQueue extends StrictList
{
    /**
     * Set the mode of iteration.
     * @see SplDoublyLinkedList::setIteratorMode
     *
     * @param int $mode The new iterator mode (0 or 1)
     *
     * @return int The set of flags and modes of iteration
     *
     * @throws \RuntimeException
     */
    final public function setIteratorMode(int $mode): int
    {
        if ($mode > 1) {
            throw new RuntimeException('Changing the iterator direction of ' . static::class . ' is prohibited.');
        }
        return parent::setIteratorMode($mode);
    }

    /**
     * Create a type-sensitive, traversable queue of items.
     *
     * @param iterable $items Initial set of items
     * @param string[] $allowedTypes Allowed types of items (optional)
     */
    public function __construct(iterable $items = [], array $allowedTypes = [])
    {
        parent::__construct($items, $allowedTypes);
        $this->setIteratorMode(0);
    }
}
