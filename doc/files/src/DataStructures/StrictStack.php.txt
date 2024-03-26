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

use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\DataStructures\Traits\StrictSplDatastructureTrait;
use RuntimeException;
use SplStack;

/**
 * A type-sensitive, taversable stack (LIFO).
 *
 * Extends [\SplStack](https://www.php.net/splstack) with an option to restrict
 * the allowed data types for list items by providing the constructor with an
 * array of atomic types or fully qualified class names.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends SplStack<AllowedType>
 */
class StrictStack extends SplStack
{
    /** @use StrictSplDatastructureTrait<AllowedType> */
    use StrictSplDatastructureTrait;

    /**
     * Add an item to the stack.
     *
     * @param AllowedType $value The item to stack
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function stack(mixed $value): void
    {
        $this->push($value);
    }

    /**
     * Unstack an item from the stack.
     *
     * @return AllowedType The unstacked item
     *
     * @throws RuntimeException if the list is empty
     *
     * @api
     */
    public function unstack(): mixed
    {
        return $this->pop();
    }
}
