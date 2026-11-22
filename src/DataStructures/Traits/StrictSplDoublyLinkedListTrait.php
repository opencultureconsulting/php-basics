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
use SplDoublyLinkedList;

use function array_reverse;
use function iterator_to_array;
use function serialize;
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
 *
 * @psalm-suppress ImmutableDependency SPL data structures are mutable by design
 * @phpstan-require-extends SplDoublyLinkedList<AllowedType>
 */
trait StrictSplDoublyLinkedListTrait
{
    /** @use TypeChecker<AllowedType, array<string|class-string<AllowedType>>> */
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
        if (!$this->hasAllowedType($value)) {
            throw InvalidDataTypeException::forParameter(2, $value);
        }
        parent::add($offset, $value);
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
        foreach ($values as $count => $value) {
            if (!$this->hasAllowedType($value)) {
                throw InvalidDataTypeException::forParameter((int) $count + 1, $value);
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
     * Create a type-sensitive list from an array of items.
     *
     * The items are pushed in the given order (like `append()`), while their
     * keys are discarded.
     *
     * @template TAllowedType of mixed
     *
     * @param array<TAllowedType> $items The items
     * @param array<string|class-string<TAllowedType>> $allowedTypes Allowed data types of items
     *                                                     (see `__construct()`)
     *
     * @return static<TAllowedType> The new list
     *
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string or contains an empty type
     * @throws InvalidDataTypeException if any of `$items` is not of allowed type
     *
     * @api
     */
    public static function fromArray(array $items, array $allowedTypes = []): static
    {
        /** @var static<TAllowedType> $list */
        $list = new static($allowedTypes);
        foreach ($items as $value) {
            $list->push($value);
        }
        return $list;
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
            throw InvalidDataTypeException::forParameter(2, $value);
        }
        /** @psalm-suppress PossiblyNullArgument */
        parent::offsetSet($offset, $value);
    }

    /**
     * Prepend items at the start of the list.
     *
     * The items keep their order, i.e. `prepend(1, 2)` on a list `[3]`
     * results in `[1, 2, 3]` (just like `array_unshift()`).
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
        foreach ($values as $count => $value) {
            if (!$this->hasAllowedType($value)) {
                throw InvalidDataTypeException::forParameter((int) $count + 1, $value);
            }
        }
        foreach (array_reverse($values) as $value) {
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
            throw InvalidDataTypeException::forParameter(1, $value);
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
     * @return list<AllowedType> Array of list items
     *
     * @api
     */
    public function toArray(): array
    {
        return iterator_to_array($this, false);
    }

    /**
     * Turn list into a type-sensitive collection.
     *
     * @return StrictCollection<int, AllowedType> A type-sensitive collection of the list's items
     *
     * @api
     */
    public function toStrictCollection(): StrictCollection
    {
        /** @var StrictCollection<int, AllowedType> $strictCollection */
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
            throw InvalidDataTypeException::forParameter(1, $value);
        }
        parent::unshift($value);
    }

    /**
     * Create a type-sensitive, traversable list of items.
     *
     * @param array<string|class-string<AllowedType>> $allowedTypes Allowed data types of items
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
     *                               - "mixed"
     *                               Union and nullable types like "int|string"
     *                               or "?int" are supported, too.
     *
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string or contains an empty type
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
        /** @var array<string|class-string<AllowedType>> $allowedTypes */
        $allowedTypes = $data['StrictSplDatastructure::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var list<AllowedType> $values */
        $values = $data['StrictSplDatastructure::dllist'];
        /** @var int $flags */
        $flags = $data['StrictSplDatastructure::flags'];
        // Items are serialized in iteration order, which is reversed in LIFO mode.
        if (($flags & SplDoublyLinkedList::IT_MODE_LIFO) !== 0) {
            $values = array_reverse($values);
        }
        $this->append(...$values);
        $this->setIteratorMode($flags);
    }
}
