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

use InvalidArgumentException;
use SplDoublyLinkedList;
use OCC\Basics\Traits\Getter;

/**
 * A type-sensitive, taversable List.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 *
 * @template AllowedTypes
 * @extends SplDoublyLinkedList<AllowedTypes>
 */
class StrictList extends SplDoublyLinkedList
{
    use Getter;

    /**
     * Defines the allowed types for items.
     *
     * If empty, all types are allowed. Possible values are:
     * - "array"
     * - "bool"
     * - "callable"
     * - "countable"
     * - "float" or "double"
     * - "int" or "integer" or "long"
     * - "iterable"
     * - "null"
     * - "numeric"
     * - "object" or FQCN
     * - "resource"
     * - "scalar"
     * - "string"
     *
     * Fully qualified class names (FQCN) can be specified instead of the
     * generic type "object".
     *
     * @var string[]
     */
    protected array $allowedTypes = [];

    /**
     * Add/insert a new item at the specified index.
     * @see SplDoublyLinkedList::add()
     *
     * @param int $index The index where the new item is to be inserted
     * @param AllowedTypes $item The new item for the index
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function add(int $index, mixed $item): void
    {
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        parent::add($index, $item);
    }

    /**
     * Append items at the end of the list.
     *
     * @param AllowedTypes ...$items One or more items to append
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function append(mixed ...$items): void
    {
        foreach ($items as $count => $item) {
            if (!$this->isAllowedType($item)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        (int) $count + 1,
                        get_debug_type($item)
                    )
                );
            }
        }
        foreach ($items as $item) {
            parent::push($item);
        }
    }

    /**
     * Get the allowed item types.
     *
     * @return string[] The list of allowed item types
     */
    public function getAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Check if item is an allowed type.
     *
     * @param AllowedTypes $item The item to check
     *
     * @return bool Whether the item is an allowed type
     */
    public function isAllowedType(mixed $item): bool
    {
        if (count($this->allowedTypes) === 0) {
            return true;
        }
        foreach ($this->allowedTypes as $type) {
            $function = 'is_' . $type;
            if (function_exists($function) && $function($item)) {
                return true;
            }
            $fqcn = '\\' . ltrim($type, '\\');
            if (is_object($item) && is_a($item, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Magic getter method for $this->allowedTypes.
     * @see Getter
     *
     * @return string[] The list of allowed item types
     */
    protected function magicGetAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Set the item at the specified index.
     * @see \ArrayAccess::offsetSet()
     *
     * @param ?int $index The index being set or NULL to append
     * @param AllowedTypes $item The new item for the index
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $index, mixed $item): void
    {
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        parent::offsetSet($index, $item);
    }

    /**
     * Prepend items at the start of the list.
     *
     * @param AllowedTypes ...$items One or more items to prepend
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function prepend(mixed ...$items): void
    {
        foreach ($items as $count => $item) {
            if (!$this->isAllowedType($item)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        (int) $count + 1,
                        get_debug_type($item)
                    )
                );
            }
        }
        foreach ($items as $item) {
            parent::unshift($item);
        }
    }

    /**
     * Push an item at the end of the list.
     * @see SplDoublyLinkedList::push()
     *
     * @param AllowedTypes $item The item to push
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function push(mixed $item): void
    {
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        parent::push($item);
    }

    /**
     * Get string representation of $this.
     * @see \Serializable::serialize()
     *
     * @return string String representation
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * Restore $this from string representation.
     * @see \Serializable::unserialize()
     *
     * @param string $data String representation
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
     * Prepend the list with an item.
     * @see SplDoublyLinkedList::unshift()
     *
     * @param AllowedTypes $item The item to unshift
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function unshift(mixed $item): void
    {
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        parent::unshift($item);
    }

    /**
     * Create a type-sensitive, traversable list of items.
     *
     * @param iterable<AllowedTypes> $items Initial set of items
     * @param string[] $allowedTypes Allowed types of items (optional)
     *
     * @throws InvalidArgumentException
     */
    public function __construct(iterable $items = [], array $allowedTypes = [])
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        $this->allowedTypes = $allowedTypes;
        $this->append(...$items);
    }

    /**
     * Get debug information for $this.
     *
     * @return mixed[] Array of debug information
     */
    public function __debugInfo(): array
    {
        return $this->__serialize();
    }

    /**
     * Get array representation of $this.
     *
     * @return mixed[] Array representation
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
     * @param mixed[] $data Array representation
     *
     * @return void
     */
    public function __unserialize(array $data): void
    {
        /** @var string[] $allowedTypes */
        $allowedTypes = $data['StrictList::allowedTypes'];
        /** @var iterable<AllowedTypes> $items */
        $items = $data['SplDoublyLinkedList::dllist'];
        $this->__construct($items, $allowedTypes);
        /** @var int $flags */
        $flags = $data['SplDoublyLinkedList::flags'];
        $this->setIteratorMode($flags);
    }
}
