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
use OCC\Basics\InterfaceTraits\ArrayAccessTrait;
use OCC\Basics\InterfaceTraits\CountableTrait;
use OCC\Basics\Traits\Getter;
use Serializable;

/**
 * A type-sensitive collection.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @property-read string[] $allowedTypes The allowed data types for items.
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
    use Getter;

    /**
     * The allowed data types for collection items.
     *
     * @var string[]
     *
     * @internal
     */
    protected array $allowedTypes = [];

    /**
     * Add/insert a new item at the specified index.
     *
     * @param array-key $key The new item's index
     * @param AllowedType $item The new item
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function add(int|string $key, mixed $item): void
    {
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        $this->data[$key] = $item;
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
        $this->data = [];
    }

    /**
     * Get the item at the specified index.
     *
     * @param array-key $key The item's index
     *
     * @return ?AllowedType The item or NULL if key is invalid
     *
     * @api
     */
    public function get(int|string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Get allowed data types for collection items.
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
     * Check if the item's data type is allowed in the collection.
     *
     * @param AllowedType $item The item to check
     *
     * @return bool Whether the item's data type is allowed
     *
     * @api
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
            /** @var class-string $fqcn */
            $fqcn = ltrim($type, '\\');
            if (is_object($item) && is_a($item, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if collection is empty.
     *
     * @return bool Whether the collection contains any items
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
     * @return bool Whether the collection is a list
     *
     * @api
     */
    public function isList(): bool
    {
        return array_is_list($this->data);
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
        return $this->getAllowedTypes();
    }

    /**
     * Set the item at the specified offset.
     *
     * @param ?array-key $offset The offset being set
     * @param AllowedType $item The new item for the offset
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @internal
     */
    public function offsetSet(mixed $offset, mixed $item): void
    {
        if (is_null($offset)) {
            throw new InvalidArgumentException(
                'Parameter 1 must be an integer or string, NULL given.'
            );
        }
        if (!$this->isAllowedType($item)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($item)
                )
            );
        }
        $this->add($offset, $item);
    }

    /**
     * Remove an item from the collection.
     *
     * @param array-key $key The item's key
     *
     * @return void
     *
     * @api
     */
    public function remove(int|string $key): void
    {
        unset($this->data[$key]);
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
     * Set allowed data types of collection items.
     *
     * @param string[] $allowedTypes Allowed data types of items
     *
     * @return void
     *
     * @throws InvalidArgumentException
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
     * Return array representation of collection.
     *
     * @return array<AllowedType> Array of collection items
     *
     * @api
     */
    public function toArray(): array
    {
        return $this->data;
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
     * @throws InvalidArgumentException
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
            'StrictCollection::allowedTypes' => $this->allowedTypes,
            'StrictCollection::items' => $this->data
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
        $allowedTypes = $data['StrictCollection::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var array<AllowedType> $items */
        $items = $data['StrictCollection::items'];
        foreach ($items as $key => $item) {
            $this->add($key, $item);
        }
    }

    /**
     * Magic method to read collection items as properties.
     *
     * @param array-key $key The item's index
     *
     * @return ?AllowedType The item or NULL if key is invalid
     */
    public function __get(int|string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Magic method to write collection items as properties.
     *
     * @param array-key $key The new item's index
     * @param AllowedType $item The new item
     *
     * @return void
     */
    public function __set(int|string $key, mixed $item): void
    {
        $this->add($key, $item);
    }
}
