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
use InvalidArgumentException;
use Iterator;
use OutOfRangeException;
use RangeException;
use RuntimeException;
use SplDoublyLinkedList;
use OCC\Basics\Traits\Getter;
use Serializable;

/**
 * A type-sensitive, taversable list.
 *
 * Extends [\SplDoublyLinkedList](https://www.php.net/spldoublylinkedlist) with
 * an option to restrict the allowed data types for list items by providing the
 * constructor with an array of atomic types or fully qualified class names.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @property-read string[] $allowedTypes The allowed data types for values.
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends SplDoublyLinkedList<AllowedType>
 * @implements ArrayAccess<int, AllowedType>
 * @implements Iterator<AllowedType>
 */
class StrictList extends SplDoublyLinkedList implements ArrayAccess, Countable, Iterator, Serializable
{
    use Getter;

    /**
     * The allowed data types for list items.
     *
     * @var string[]
     *
     * @internal
     */
    protected array $allowedTypes = [];

    /**
     * Add/insert a new item at the specified offset.
     *
     * @param int $offset The offset where the new item is to be inserted
     * @param AllowedType $value The new item for the offset
     *
     * @return void
     *
     * @throws InvalidArgumentException if `$value` is not of allowed type
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
     * @throws InvalidArgumentException if any `$values` is not of allowed type
     *
     * @api
     */
    public function append(mixed ...$values): void
    {
        /** @var array<int, AllowedType> $values */
        foreach ($values as $count => $value) {
            if (!$this->isAllowedType($value)) {
                throw new InvalidArgumentException(
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
     * Peek at the item at the beginning of the list.
     *
     * @return AllowedType The first item of the list
     *
     * @throws RuntimeException if the list is empty
     *
     * @api
     */
    public function bottom(): mixed
    {
        return parent::bottom();
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
     * Get the number of items on the list.
     *
     * @return int The number of items on the list
     *
     * @api
     */
    public function count(): int
    {
        return parent::count();
    }

    /**
     * Get the current list item.
     *
     * @return AllowedType The current item
     *
     * @api
     */
    public function current(): mixed
    {
        return parent::current();
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
     * Get allowed data types for list items.
     *
     * @return string[] The list of allowed data types
     *
     * @api
     */
    public function getAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Get the mode of iteration.
     *
     * @return int The set of flags and modes of iteration
     *
     * @api
     */
    public function getIteratorMode(): int
    {
        return parent::getIteratorMode();
    }

    /**
     * Check if the item's data type is allowed on the list.
     *
     * @param AllowedType $value The item to check
     *
     * @return bool Whether the item's data type is allowed
     *
     * @api
     */
    public function isAllowedType(mixed $value): bool
    {
        if (count($this->allowedTypes) === 0) {
            return true;
        }
        foreach ($this->allowedTypes as $type) {
            $function = 'is_' . $type;
            if (function_exists($function) && $function($value)) {
                return true;
            }
            /** @var class-string $fqcn */
            $fqcn = ltrim($type, '\\');
            if (is_object($value) && is_a($value, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if list is empty.
     *
     * @return bool Whether the list contains no items
     *
     * @api
     */
    public function isEmpty(): bool
    {
        return parent::isEmpty();
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
     * Get the current list index.
     *
     * @return int The current list index
     *
     * @api
     */
    public function key(): int
    {
        return parent::key();
    }

    /**
     * Move the cursor to the next list index.
     *
     * @return void
     *
     * @api
     */
    public function next(): void
    {
        parent::next();
    }

    /**
     * Check if the specified index exists and is not empty.
     *
     * @param int $offset The index to check
     *
     * @return bool Whether the index exists and is not empty
     *
     * @api
     */
    public function offsetExists(mixed $offset): bool
    {
        return parent::offsetExists($offset);
    }

    /**
     * Get the item from the specified index.
     *
     * @param int $offset The item's index
     *
     * @return AllowedType The item
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function offsetGet(mixed $offset): mixed
    {
        return parent::offsetGet($offset);
    }

    /**
     * Set the item at the specified offset.
     *
     * @param ?int $offset The offset being set or NULL to append
     * @param AllowedType $value The new item for the offset
     *
     * @return void
     *
     * @throws InvalidArgumentException if `$value` is not of allowed type
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
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
     * Unset the item at the specified index.
     *
     * @param int $offset The item's index
     *
     * @return void
     *
     * @throws OutOfRangeException when `$offset` is out of bounds
     *
     * @api
     */
    public function offsetUnset(mixed $offset): void
    {
        parent::offsetUnset($offset);
    }

    /**
     * Pops an item from the end of the list.
     *
     * @return AllowedType The item from the end of the list
     *
     * @throws RuntimeException if the list is empty
     *
     * @api
     */
    public function pop(): mixed
    {
        return parent::pop();
    }

    /**
     * Prepend items at the start of the list.
     *
     * @param AllowedType ...$values One or more items to prepend
     *
     * @return void
     *
     * @throws InvalidArgumentException if `$value` is not of allowed type
     *
     * @api
     */
    public function prepend(mixed ...$values): void
    {
        /** @var array<int, AllowedType> $values */
        foreach ($values as $count => $value) {
            if (!$this->isAllowedType($value)) {
                throw new InvalidArgumentException(
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
     * Move the cursor to the previous list index.
     *
     * @return void
     *
     * @api
     */
    public function prev(): void
    {
        parent::prev();
    }

    /**
     * Push an item at the end of the list.
     *
     * @param AllowedType $value The item to push
     *
     * @return void
     *
     * @throws InvalidArgumentException if `$value` is not of allowed type
     *
     * @api
     */
    public function push(mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
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
     * Rewind the iterator's cursor.
     *
     * @return void
     *
     * @api
     */
    public function rewind(): void
    {
        parent::rewind();
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
     * @throws InvalidArgumentException if `$value` is not of allowed type
     *
     * @api
     */
    public function set(int $offset, mixed $value): void
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Set allowed data types of list items.
     *
     * @param string[] $allowedTypes Allowed data types of items
     *
     * @return void
     *
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string
     */
    protected function setAllowedTypes(array $allowedTypes = []): void
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        $this->allowedTypes = $allowedTypes;
    }

    /**
     * Set the mode of iteration.
     *
     * @param int $mode The new iterator mode (0, 1, 2 or 3)
     *
     *                  There are two orthogonal sets of modes that can be set.
     *
     *                  The direction of iteration (either one or the other):
     *                  - StrictList::IT_MODE_FIFO (queue style)
     *                  - StrictList::IT_MODE_LIFO (stack style)
     *
     *                  The behavior of the iterator (either one or the other):
     *                  - StrictList::IT_MODE_DELETE (delete items)
     *                  - StrictList::IT_MODE_KEEP (keep items)
     *
     *                  The default mode is: IT_MODE_FIFO | IT_MODE_KEEP
     *
     * @return int The set of flags and modes of iteration
     *
     * @throws RangeException if an invalid `$mode` is given
     *
     * @api
     */
    public function setIteratorMode(int $mode): int
    {
        if (!in_array($mode, range(0, 3), true)) {
            throw new RangeException(
                sprintf(
                    'Iterator mode must be an integer in range [0..3], %d given.',
                    $mode
                )
            );
        }
        return parent::setIteratorMode($mode);
    }

    /**
     * Shift an item from the beginning of the list.
     *
     * @return AllowedType The first item of the list
     *
     * @throws RuntimeException if the list is empty
     *
     * @api
     */
    public function shift(): mixed
    {
        return parent::shift();
    }

    /**
     * Return array representation of list.
     *
     * @return AllowedType[] Array of list items
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
        $strictCollection = new StrictCollection($this->allowedTypes);
        foreach ($this->toArray() as $offset => $value) {
            $strictCollection[$offset] = $value;
        }
        return $strictCollection;
    }

    /**
     * Peek at the item at the end of the list.
     *
     * @return AllowedType The last item of the list
     *
     * @throws RuntimeException if the list is empty
     *
     * @api
     */
    public function top(): mixed
    {
        return parent::top();
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
     * @throws InvalidArgumentException if `$value` is not of allowed type
     *
     * @api
     */
    public function unshift(mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        parent::unshift($value);
    }

    /**
     * Check if current cursor position is valid.
     *
     * @return bool Whether the current cursor position is valid
     *
     * @api
     */
    public function valid(): bool
    {
        return parent::valid();
    }

    /**
     * Magic getter method for $this->allowedTypes.
     *
     * @return string[] The list of allowed data types
     *
     * @internal
     */
    protected function _magicGetAllowedTypes(): array
    {
        return $this->getAllowedTypes();
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
            'StrictList::allowedTypes' => $this->allowedTypes,
            'SplDoublyLinkedList::dllist' => iterator_to_array($this),
            'SplDoublyLinkedList::flags' => $this->getIteratorMode()
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
        $allowedTypes = $data['StrictList::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var array<int, AllowedType> $values */
        $values = $data['SplDoublyLinkedList::dllist'];
        $this->append(...$values);
        /** @var int $flags */
        $flags = $data['SplDoublyLinkedList::flags'];
        $this->setIteratorMode($flags);
    }
}
