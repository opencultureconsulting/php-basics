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

use Iterator;
use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\Interfaces\IteratorTrait;
use RuntimeException;

use function array_key_first;
use function array_key_last;
use function array_pop;
use function array_push;
use function array_shift;
use function array_unshift;
use function count;
use function get_debug_type;
use function is_null;
use function sprintf;

/**
 * A type-sensitive, traversable array.
 *
 * Holds items as key/value pairs where keys have to be valid array keys while
 * values can be of any type. To restrict allowed data types for items, provide
 * the constructor with an array of atomic types or fully qualified class
 * names.
 *
 * Internally it holds the items in the protected `$_data` array.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends StrictCollection<AllowedType>
 * @implements Iterator<AllowedType>
 */
class StrictArray extends StrictCollection implements Iterator
{
    /** @use IteratorTrait<AllowedType> */
    use IteratorTrait;

    /**
     * Peek at the first item of the array.
     *
     * @return AllowedType The first item of the array
     *
     * @throws RuntimeException if the array is empty
     *
     * @api
     */
    public function bottom(): mixed
    {
        $key = array_key_first($this->_data);
        if (is_null($key)) {
            throw new RuntimeException(
                'Cannot return bottom item: array is empty.'
            );
        }
        return $this->_data[$key];
    }

    /**
     * Pop the item from the end of the array.
     *
     * @return AllowedType The last item of the array
     *
     * @throws RuntimeException if the array is empty
     *
     * @api
     */
    public function pop(): mixed
    {
        if (count($this->_data) === 0) {
            throw new RuntimeException(
                'Cannot return last item: array is empty.'
            );
        }
        return array_pop($this->_data);
    }

    /**
     * Push an item at the end of the array.
     *
     * @param AllowedType $value The item to push
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function push(mixed $value): void
    {
        if (!$this->hasAllowedType($value)) {
            throw new InvalidDataTypeException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        array_push($this->_data, $value);
    }

    /**
     * Shift the item from the beginning of the array.
     *
     * @return AllowedType The first item of the array
     *
     * @throws RuntimeException if the array is empty
     *
     * @api
     */
    public function shift(): mixed
    {
        if (count($this->_data) === 0) {
            throw new RuntimeException(
                'Cannot return first item: array is empty.'
            );
        }
        return array_shift($this->_data);
    }

    /**
     * Peek at the last item of the array.
     *
     * @return AllowedType The last item of the array
     *
     * @throws RuntimeException if the array is empty
     *
     * @api
     */
    public function top(): mixed
    {
        $key = array_key_last($this->_data);
        if (is_null($key)) {
            throw new RuntimeException(
                'Cannot return top item: array is empty.'
            );
        }
        return $this->_data[$key];
    }

    /**
     * Prepend the array with an item.
     *
     * @param AllowedType $value The item to unshift
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function unshift(mixed $value): void
    {
        if (!$this->hasAllowedType($value)) {
            throw new InvalidDataTypeException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        array_unshift($this->_data, $value);
    }
}
