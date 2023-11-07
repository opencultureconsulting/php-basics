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

use Countable;
use InvalidArgumentException;
use Iterator;
use OCC\Basics\Traits\Getter;
use Serializable;

/**
 * Abstract class for a type-sensitive list of items.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
abstract class AbstractStrictList implements Countable, Iterator, Serializable
{
    use Getter;

    /**
     * The items.
     */
    protected array $items = [];

    /**
     * Count of iterations.
     */
    protected int $iterations = 0;

    /**
     * Defines the allowed types for items.
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
     * Append items.
     *
     * @param mixed ...$items One or more items to add
     *
     * @return void
     */
    public function append(mixed ...$items): void
    {
        if (!empty($this->allowedTypes)) {
            foreach ($items as $count => $item) {
                if (!$this->isAllowedType($item)) {
                    throw new InvalidArgumentException('Item ' . $count + 1 . ' must be an allowed type, ' . get_debug_type($item) . ' given.');
                }
            }
        }
        $this->items = array_merge($this->items, $items);
    }

    /**
     * Get the number of items.
     * @see Countable::count
     *
     * @return int The number of items
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Clear all items.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];
        $this->rewind();
    }

    /**
     * Get and remove the current item.
     * @see Iterator::current
     *
     * @return mixed The current item or NULL if empty
     */
    abstract public function current(): mixed;

    /**
     * Check if an item is an allowed type.
     *
     * @param mixed $item The item to check
     *
     * @return bool Whether the item is an allowed type
     */
    protected function isAllowedType(mixed $item): bool
    {
        if (empty($this->allowedTypes)) {
            return true;
        }
        foreach ($this->allowedTypes as $type) {
            $function = 'is_' . $type;
            $fqcn = '\\' . ltrim($type, '\\');
            if (function_exists($function) && $function($item)) {
                return true;
            }
            if (is_object($item) && is_a($item, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the number of the current iteration.
     * @see Iterator::key
     *
     * @return int The number of the current iteration
     */
    public function key(): int
    {
        return $this->iterations;
    }

    /**
     * Magic getter method for $this->allowedTypes.
     * @see OCC\Basics\Traits\Getter
     *
     * @return array The list of allowed item types
     */
    protected function magicGetAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Count the next iteration.
     * @see Iterator::next
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->iterations;
    }

    /**
     * Get the current item without removing it.
     *
     * @return mixed The current item or NULL if empty
     */
    abstract public function peek(): mixed;

    /**
     * Reset the iterator position.
     * @see Iterator::rewind
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->iterations = 0;
    }

    /**
     * Get string representation of $this.
     * @see Serializable::serialize
     *
     * @return ?string String representation
     */
    public function serialize(): ?string
    {
        return serialize($this->__serialize());
    }

    /**
     * Restore $this from string representation.
     * @see Serializable::unserialize
     *
     * @param string $data String representation
     *
     * @return void
     */
    public function unserialize($data): void
    {
        $this->__unserialize(unserialize($data));
    }

    /**
     * Check if there are any items.
     * @see Iterator::valid
     *
     * @return bool Whether there are items
     */
    public function valid(): bool
    {
        return (bool) $this->items;
    }

    /**
     * Reset iteration counter after cloning.
     *
     * @return void
     */
    public function __clone(): void
    {
        $this->rewind();
    }

    /**
     * Create a (type-sensitive) traversable set of items.
     *
     * @param array $items Initial set of items
     * @param string[] $allowedTypes Allowed types of items (optional)
     */
    public function __construct(array $items = [], array $allowedTypes = [])
    {
        if (!empty($allowedTypes)) {
            if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
                throw new InvalidArgumentException('Allowed types must be array of strings or empty array.');
            }
            $this->allowedTypes = $allowedTypes;
        }
        $this->append(...$items);
    }

    /**
     * Get debug information for $this.
     *
     * @return array Array of debug information
     */
    public function __debugInfo(): array
    {
        return $this->__serialize();
    }

    /**
     * Get array representation of $this.
     *
     * @return array Array representation
     */
    public function __serialize(): array
    {
        return [
            'allowedTypes' => $this->allowedTypes,
            'items' => $this->items
        ];
    }

    /**
     * Restore $this from array representation.
     *
     * @param array $data Array representation
     *
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->allowedTypes = $data['allowedTypes'] ?? [];
        $this->items = $data['items'] ?? [];
    }
}
