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

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use OCC\Basics\Traits\Getter;
use OutOfBoundsException;
use OutOfRangeException;
use SeekableIterator;

/**
 * Handles an ordered queue of items - optionally type-sensitive.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements ArrayAccess
 * @implements Countable
 * @implements SeekableIterator
 */
class Queue implements ArrayAccess, Countable, SeekableIterator
{
    use Getter;

    /**
     * Holds the queue's elements.
     */
    protected array $queue = [];

    /**
     * Holds the queue's current index.
     */
    protected int $index = 0;

    /**
     * Defines the allowed types for the queue's elements.
     *  If empty, all types are allowed.
     *  Possible values are:
     *  - "array"
     *  - "bool"
     *  - "callable"
     *  - "countable"
     *  - "float" or "double"
     *  - "int" or "integer" or "long"
     *  - "iterable"
     *  - "null"
     *  - "numeric"
     *  - "object" or FQCN
     *  - "resource"
     *  - "scalar"
     *  - "string"
     *  Fully qualified class names (FQCN) can be specified instead of the
     *  generic type "object".
     */
    protected array $allowedTypes = [];

    /**
     * Check if a variable is an allowed type.
     *
     * @param mixed $var The variable to check
     *
     * @return bool Whether the variable is an allowed type
     */
    protected function isAllowedType(mixed $var): bool
    {
        if (empty($this->allowedTypes)) {
            return true;
        }
        foreach ($this->allowedTypes as $type) {
            $function = 'is_' . $type;
            $fqcn = '\\' . ltrim($type, '\\');
            if (function_exists($function) && $function($var)) {
                return true;
            }
            if (is_object($var) && is_a($var, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if a given index is in the range of valid indexes.
     *
     * @param mixed $offset The index to check
     * @param bool $allowAppend Should the next free index be valid as well?
     *
     * @return bool Whether the given index is in valid range
     */
    protected function isIndexInRange(mixed $offset, bool $allowAppend = false): bool
    {
        $options = [
            'options' => [
                'min_range' => 0,
                'max_range' => count($this->queue) - ($allowAppend ? 0 : 1)
            ]
        ];
        return filter_var($offset, FILTER_VALIDATE_INT, $options) !== false;
    }

    /**
     * Check if a given index exists on the queue.
     * @see ArrayAccess::offsetExists
     *
     * @param int $offset The queue's index to check
     *
     * @return bool Whether the given index is valid
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->queue[$offset]);
    }

    /**
     * Get the element with given index from the queue.
     * @see ArrayAccess::offsetGet
     *
     * @param int $offset The queue's index to get
     *
     * @return ?mixed The queue's element at given index or NULL
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->queue[$offset] ?? null;
    }

    /**
     * Set the element at given index in the queue.
     * @see ArrayAccess::offsetSet
     *
     * @param ?int $offset The queue's index to set or NULL to append
     *                     Must be between 0 and the length of the queue.
     * @param mixed $value The element to set at the given index
     *                     Must be of an allowed type if applicable.
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $offset = count($this->queue);
        } elseif (!$this->isIndexInRange($offset, true)) {
            throw new OutOfRangeException('Index must be an integer between 0 and the length of the queue (' . count($this->queue) . ') or NULL to append: ' . get_debug_type($offset) . (is_int($offset) ? ' ' . (string) $offset : '') . ' given.');
        }
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException('Value must be one of ' . implode(', ', $this->allowedTypes) . ': ' . get_debug_type($value) . ' given.');
        }
        $this->queue[(int) $offset] = $value;
    }

    /**
     * Remove the element with given index from the queue.
     * @see ArrayAccess::offsetUnset
     *
     * @param int $offset The queue's index to unset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this->isIndexInRange($offset)) {
            array_splice($this->queue, (int) $offset, 1);
            if ((int) $offset <= $this->index) {
                --$this->index;
            }
        }
    }

    /**
     * Get the number of elements in the queue.
     * @see Countable::count
     *
     * @return int The number of items in the queue
     */
    public function count(): int
    {
        return count($this->queue);
    }

    /**
     * Get the current element from the queue.
     * @see Iterator::current
     *
     * @return mixed|null The queue's current element or NULL
     */
    public function current(): mixed
    {
        return $this->queue[$this->index] ?? null;
    }

    /**
     * Get the current index from the queue.
     * @see Iterator::key
     *
     * @return int The queue's current index
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * Move the index to next element of the queue.
     * @see Iterator::next
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->index;
    }

    /**
     * Reset the index to the first element of the queue.
     * @see Iterator::rewind
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * Check if the queue's current index is valid.
     * @see Iterator::valid
     *
     * @return bool Whether the queue's current index is valid
     */
    public function valid(): bool
    {
        return isset($this->queue[$this->index]);
    }

    /**
     * Sets the queue's current index.
     * @see SeekableIterator::seek
     *
     * @param int $offset The queue's new index
     *
     * @return void
     *
     * @throws OutOfBoundsException
     */
    public function seek(int $offset): void
    {
        if (!$this->isIndexInRange($offset)) {
            throw new OutOfBoundsException('Index must be an integer between 0 and the length of the queue (' . count($this->queue) . '): ' . (string) $offset . ' given.');
        }
        $this->index = $offset;
    }

    /**
     * Magic getter method for $this->allowedTypes.
     * @see OCC\Traits\Getter
     *
     * @return array The list of the queue's allowed element types
     */
    protected function magicGetAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Create a (type-sensitive) queue of elements.
     * @see OCC\Helper\Queue::allowedTypes
     *
     * @param string[] $allowedTypes Allowed types of queue's elements
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $allowedTypes = [])
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException('Parameter 1 must be array of strings or empty array.');
        }
        $this->allowedTypes = $allowedTypes;
    }
}
