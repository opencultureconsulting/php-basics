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

namespace OCC\Basics\DataStructures\Traits;

use InvalidArgumentException;
use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\DataStructures\StrictCollection;
use OCC\Basics\Traits\TypeChecker;
use OutOfRangeException;

use function get_debug_type;
use function iterator_to_array;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * The common interface of all type-sensitive, SPL-based datastructures.
 *
 * This extends all methods of the common interface of the Standard PHP Library
 * [Doubly Linked List Datastructures](https://www.php.net/spl.datastructures)
 * by type-checking to only allow specified data types on the list.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @template AllowedType of mixed
 */
trait StrictSplDatastructureTrait
{
    use TypeChecker {
        setAllowedTypes as protected;
    }

    /**
     * Add/insert a new item at the specified offset.
     *
     * @param int $offset The offset where the new item is to be inserted
     * @param AllowedType $value The new item for the offset
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function add(int $offset, mixed $value): void
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Append items at the end of the list.
     *
     * @param AllowedType ...$values One or more items to append
     *
     * @return void
     *
     * @throws InvalidDataTypeException if any `$values` is not of allowed type
     *
     * @api
     */
    public function append(mixed ...$values): void
    {
        /** @var array<int, AllowedType> $values */
        foreach ($values as $count => $value) {
            if (!$this->hasAllowedType($value)) {
                throw new InvalidDataTypeException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        $count + 1,
                        get_debug_type($value)
                    )
                );
            }
        }
        foreach ($values as $value) {
            parent::push($value);
        }
    }

    /**
     * Clear the list of any items.
     *
     * @return void
     *
     * @api
     */
    public function clear(): void
    {
        while (!$this->isEmpty()) {
            $this->pop();
        }
        $this->rewind();
    }

    /**
     * Get the item at the specified index.
     *
     * @param int $offset The item's index
     *
     * @return AllowedType The item
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function get(int $offset): mixed
    {
        return $this->offsetGet($offset);
    }

    /**
     * Check if this can be considered a list.
     *
     * @return true Always TRUE (this exists only for compatibility reasons)
     *
     * @api
     */
    public function isList(): bool
    {
        return true;
    }

    /**
     * Set the item at the specified offset.
     *
     * @param ?int $offset The offset being set or NULL to append
     * @param AllowedType $value The new item for the offset
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$this->hasAllowedType($value)) {
            throw new InvalidDataTypeException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        /** @psalm-suppress PossiblyNullArgument */
        parent::offsetSet($offset, $value);
    }

    /**
     * Prepend items at the start of the list.
     *
     * @param AllowedType ...$values One or more items to prepend
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function prepend(mixed ...$values): void
    {
        /** @var array<int, AllowedType> $values */
        foreach ($values as $count => $value) {
            if (!$this->hasAllowedType($value)) {
                throw new InvalidDataTypeException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        $count + 1,
                        get_debug_type($value)
                    )
                );
            }
        }
        foreach ($values as $value) {
            parent::unshift($value);
        }
    }

    /**
     * Push an item at the end of the list.
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
        parent::push($value);
    }

    /**
     * Remove an item from the list.
     *
     * @param int $offset The item's index
     *
     * @return void
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function remove(int $offset): void
    {
        $this->offsetUnset($offset);
    }

    /**
     * Get string representation of $this.
     *
     * @return string The string representation
     *
     * @internal
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * Set an item at the specified index.
     *
     * @param int $offset The item's index
     * @param AllowedType $value The item
     *
     * @return void
     *
     * @throws InvalidDataTypeException if `$value` is not of allowed type
     *
     * @api
     */
    public function set(int $offset, mixed $value): void
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Return array representation of list.
     *
     * @return array<int, AllowedType> Array of list items
     *
     * @api
     */
    public function toArray(): array
    {
        return iterator_to_array($this, true);
    }

    /**
     * Turn list into a type-sensitive collection.
     *
     * @return StrictCollection<AllowedType> A type-sensitive collection of the list's items
     *
     * @api
     */
    public function toStrictCollection(): StrictCollection
    {
        $strictCollection = new StrictCollection($this->getAllowedTypes());
        foreach ($this->toArray() as $offset => $value) {
            $strictCollection[$offset] = $value;
        }
        return $strictCollection;
    }

    /**
     * Restore $this from string representation.
     *
     * @param string $data The string representation
     *
     * @return void
     *
     * @internal
     */
    public function unserialize($data): void
    {
        /** @var mixed[] $dataArray */
        $dataArray = unserialize($data);
        $this->__unserialize($dataArray);
    }

    /**
     * Prepend the list with an item.
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
        parent::unshift($value);
    }

    /**
     * Create a type-sensitive, traversable list of items.
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
            'StrictSplDatastructure::allowedTypes' => $this->getAllowedTypes(),
            'StrictSplDatastructure::dllist' => $this->toArray(),
            'StrictSplDatastructure::flags' => $this->getIteratorMode()
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
     * @psalm-suppress MethodSignatureMismatch
     */
    public function __unserialize(array $data): void
    {
        /** @var string[] $allowedTypes */
        $allowedTypes = $data['StrictSplDatastructure::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var array<int, AllowedType> $values */
        $values = $data['StrictSplDatastructure::dllist'];
        $this->append(...$values);
        /** @var int $flags */
        $flags = $data['StrictSplDatastructure::flags'];
        $this->setIteratorMode($flags);
    }
}
