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

use InvalidArgumentException;
use SplDoublyLinkedList;
use OCC\Basics\Traits\Getter;

/**
 * A type-sensitive, taversable list.
 *
 * Extends [\SplDoublyLinkedList](https://www.php.net/spldoublylinkedlist) with
 * an option to specify the allowed data types for list items.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @property-read string[] $allowedTypes
 *
 * @template AllowedType of mixed
 * @extends SplDoublyLinkedList<AllowedType>
 */
class StrictList extends SplDoublyLinkedList
{
    use Getter;

    /**
     * Defines the allowed data types for items.
     *
     * @var string[]
     */
    protected array $allowedTypes = [];

    /**
     * Add/insert a new item at the specified index.
     *
     * @param int $index The index where the new item is to be inserted
     * @param AllowedType $item The new item for the index
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
     * @param AllowedType ...$items One or more items to append
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
     * Check if the item's data type is allowed on the list.
     *
     * @param AllowedType $item The item to check
     *
     * @return bool Whether the item's data type is allowed
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
            $fqcn = ltrim($type, '\\');
            if (is_object($item) && is_a($item, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Magic getter method for $this->allowedTypes.
     *
     * @return string[] The list of allowed data types
     *
     * @internal
     */
    protected function magicGetAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Set the item at the specified index.
     *
     * @param ?int $index The index being set or NULL to append
     * @param AllowedType $item The new item for the index
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
     * @param AllowedType ...$items One or more items to prepend
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
     *
     * @param AllowedType $item The item to push
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
     *
     * @return string The string representation
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
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
     * Prepend the list with an item.
     *
     * @param AllowedType $item The item to unshift
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
     * @param string[] $allowedTypes Allowed data types of items (optional)
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
     * @throws InvalidArgumentException
     */
    public function __construct(array $allowedTypes = [])
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        $this->allowedTypes = $allowedTypes;
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
     */
    public function __unserialize(array $data): void
    {
        /** @var string[] $allowedTypes */
        $allowedTypes = $data['StrictList::allowedTypes'];
        $this->__construct($allowedTypes);
        /** @var iterable<AllowedType> $items */
        $items = $data['SplDoublyLinkedList::dllist'];
        $this->append(...$items);
        /** @var int $flags */
        $flags = $data['SplDoublyLinkedList::flags'];
        $this->setIteratorMode($flags);
    }
}
