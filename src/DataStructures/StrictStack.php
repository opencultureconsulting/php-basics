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
 * A type-sensitive, taversable Last In, First Out stack (LIFO).
 *
 * Extends [\SplStack](https://www.php.net/splstack) with an option to specify
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
class StrictStack extends StrictList implements ArrayAccess, Countable, Iterator, Serializable
{
    /**
     * Add an item to the stack.
     *
     * @param AllowedType $item The item to stack
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function stack(mixed $item): void
    {
        parent::push($item);
    }

    /**
     * Unstack an item from the stack.
     *
     * @return AllowedType The unstacked item
     *
     * @api
     */
    public function unstack(): mixed
    {
        return parent::pop();
    }

    /**
     * Set the mode of iteration.
     *
     * @param int $mode The new iterator mode (2 or 3)
     *
     *                  There are two orthogonal sets of modes that can be set.
     *
     *                  The direction of iteration (fixed for StrictStack):
     *                  - StrictStack::IT_MODE_LIFO (stack style)
     *
     *                  The behavior of the iterator (either one or the other):
     *                  - StrictStack::IT_MODE_DELETE (delete items)
     *                  - StrictStack::IT_MODE_KEEP (keep items)
     *
     *                  The default mode is: IT_MODE_LIFO | IT_MODE_KEEP
     *
     * @return int The set of flags and modes of iteration
     *
     * @throws RuntimeException
     *
     * @api
     */
    final public function setIteratorMode(int $mode): int
    {
        if ($mode < 2) {
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
     * Create a type-sensitive, traversable stack of items.
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
        $this->setIteratorMode(2);
    }
}
