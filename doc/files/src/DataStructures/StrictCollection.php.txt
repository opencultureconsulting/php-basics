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
use DomainException;
use InvalidArgumentException;
use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\Interfaces\ArrayAccessTrait;
use OCC\Basics\Interfaces\CountableTrait;
use OCC\Basics\Traits\TypeChecker;
use OutOfRangeException;
use Serializable;

use function array_is_list;
use function get_debug_type;
use function is_integer;
use function is_string;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * A type-sensitive, unsorted collection.
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
 * @implements ArrayAccess<array-key, AllowedType>
 */
class StrictCollection implements ArrayAccess, Countable, Serializable
{
    /** @use ArrayAccessTrait<AllowedType> */
    use ArrayAccessTrait;
    /** @use CountableTrait<AllowedType> */
    use CountableTrait;
    use TypeChecker {
        setAllowedTypes as protected;
    }

    /**
     * Holds the collection's items.
     *
     * @var array<array-key, AllowedType>
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Add/insert a item at the specified index.
     *
     * @param array-key $offset The item's index
     * @param AllowedType $value The item
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function add(int|string $offset, mixed $value): void
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Clear the collection of any items.
     *
     * @return void
     *
     * @api
     */
    public function clear(): void
    {
        $this->_data = [];
    }

    /**
     * Get the item at the specified index.
     *
     * @param array-key $offset The item's index
     *
     * @return AllowedType The item
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function get(int|string $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfRangeException(
                sprintf(
                    'Offset %s is not a valid index key for this collection.',
                    $offset
                )
            );
        }
        /** @var AllowedType $value */
        $value = $this->offsetGet($offset);
        return $value;
    }

    /**
     * Check if collection is empty.
     *
     * @return bool Whether the collection contains no items
     *
     * @api
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Check if this collection can be considered a list.
     *
     * It is considered a list if all keys are consecutive integers starting
     * from `0`.
     *
     * @return bool Whether the collection is a list
     *
     * @api
     *
     * @see StrictCollection::toStrictList()
     */
    public function isList(): bool
    {
        return array_is_list($this->_data);
    }

    /**
     * Set the item at the specified offset.
     *
     * @param ?array-key $offset The offset being set
     * @param AllowedType $value The new item for the offset
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     * @throws InvalidArgumentException if `$offset` is not a valid array key
     *
     * @api
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_integer($offset) && !is_string($offset)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an integer or string, %s given.',
                    get_debug_type($offset)
                )
            );
        }
        if (!$this->hasAllowedType($value)) {
            throw new InvalidDataTypeException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        $this->_data[$offset] = $value;
    }

    /**
     * Remove an item from the collection.
     *
     * @param array-key $offset The item's key
     *
     * @return void
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function remove(int|string $offset): void
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfRangeException(
                sprintf(
                    'Offset %s is not a valid index key for this collection.',
                    $offset
                )
            );
        }
        $this->offsetUnset($offset);
    }

    /**
     * Get string representation of $this.
     *
     * @return string The string representation
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * Set an item at the specified index.
     *
     * @param array-key $offset The item's index
     * @param AllowedType $value The item
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function set(int|string $offset, mixed $value): void
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Return array representation of collection.
     *
     * @return array<array-key, AllowedType> Array of collection items
     *
     * @api
     */
    public function toArray(): array
    {
        return $this->_data;
    }

    /**
     * Turn collection into a type-sensitive list.
     *
     * @return StrictList<AllowedType> A type-sensitive list of the collection's items
     *
     * @throws DomainException if the collection is not a list
     *
     * @api
     *
     * @see StrictCollection::isList()
     */
    public function toStrictList(): StrictList
    {
        if (!$this->isList()) {
            throw new DomainException(
                'Cannot convert into StrictList: collection contains non-integer and/or non-consecutive keys.'
            );
        }
        $strictList = new StrictList($this->getAllowedTypes());
        $strictList->append(...$this->toArray());
        return $strictList;
    }

    /**
     * Restore $this from string representation.
     *
     * @param string $data The string representation
     *
     * @return void
     */
    public function unserialize($data): void
    {
        /** @var mixed[] $dataArray */
        $dataArray = unserialize($data);
        $this->__unserialize($dataArray);
    }

    /**
     * Create a type-sensitive collection of items.
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
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string
     */
    public function __construct(array $allowedTypes = [])
    {
        $this->setAllowedTypes($allowedTypes);
    }

    /**
     * Get debug information for $this.
     *
     * @return mixed[] The debug information
     *
     * @internal
     */
    public function __debugInfo(): array
    {
        return $this->__serialize();
    }

    /**
     * Get array representation of $this.
     *
     * @return mixed[] The array representation
     *
     * @internal
     */
    public function __serialize(): array
    {
        return [
            'StrictCollection::allowedTypes' => $this->getAllowedTypes(),
            'StrictCollection::items' => $this->toArray()
        ];
    }

    /**
     * Restore $this from array representation.
     *
     * @param mixed[] $data The array representation
     *
     * @return void
     *
     * @internal
     *
     * @hpsalm-suppress MethodSignatureMismatch
     */
    public function __unserialize(array $data): void
    {
        /** @var string[] $allowedTypes */
        $allowedTypes = $data['StrictCollection::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var array<array-key, AllowedType> $items */
        $items = $data['StrictCollection::items'];
        foreach ($items as $offset => $value) {
            $this->offsetSet($offset, $value);
        }
    }
}
