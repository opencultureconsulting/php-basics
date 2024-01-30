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
use RuntimeException;
use Serializable;

/**
 * A type-sensitive, taversable First In, First Out queue (FIFO).
 *
 * Extends [\SplQueue](https://www.php.net/splqueue) with an option to specify
 * the allowed data types for list items.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends StrictList<AllowedType>
 * @implements ArrayAccess<int, AllowedType>
 * @implements Iterator<AllowedType>
 */
class StrictQueue extends StrictList implements ArrayAccess, Countable, Iterator, Serializable
{
    /**
     * Dequeue an item from the queue.
     *
     * @return AllowedType The dequeued item
     *
     * @api
     */
    public function dequeue(): mixed
    {
        return parent::shift();
    }

    /**
     * Add an item to the queue.
     *
     * @param AllowedType $item The item to enqueue
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function enqueue(mixed $item): void
    {
        parent::push($item);
    }

    /**
     * Set the mode of iteration.
     *
     * @param int $mode The new iterator mode (0 or 1)
     *
     *                  There are two orthogonal sets of modes that can be set.
     *
     *                  The direction of iteration (fixed for StrictQueue):
     *                  - StrictQueue::IT_MODE_FIFO (queue style)
     *
     *                  The behavior of the iterator (either one or the other):
     *                  - StrictQueue::IT_MODE_DELETE (delete items)
     *                  - StrictQueue::IT_MODE_KEEP (keep items)
     *
     *                  The default mode is: IT_MODE_FIFO | IT_MODE_KEEP
     *
     * @return int The set of flags and modes of iteration
     *
     * @throws RuntimeException
     *
     * @api
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
     * @param string[] $allowedTypes Allowed data types of items (optional)
     *
     *                               If empty, all types are allowed.
     *                               Possible values are:
     *                               - "array"
     *                               - "bool"
     *                               - "callable"
     *                               - "countable"
     *                               - "float" or "double"
     *                               - "int" or "integer" or "long"
     *                               - "iterable"
     *                               - "null"
     *                               - "numeric"
     *                               - "object" or FQCN
     *                               - "resource"
     *                               - "scalar"
     *                               - "string"
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $allowedTypes = [])
    {
        parent::__construct($allowedTypes);
        $this->setIteratorMode(0);
    }
}
