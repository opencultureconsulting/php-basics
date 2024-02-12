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
use IteratorAggregate;
use OCC\Basics\InterfaceTraits\ArrayAccessTrait;
use OCC\Basics\InterfaceTraits\CountableTrait;
use OCC\Basics\InterfaceTraits\IteratorAggregateTrait;

/**
 * A generic collection of items.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template Item of mixed
 * @implements ArrayAccess<array-key, Item>
 * @implements IteratorAggregate<Item>
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /** @use ArrayAccessTrait<Item> */
    use ArrayAccessTrait;
    /** @use CountableTrait<Item> */
    use CountableTrait;
    /** @use IteratorAggregateTrait<Item> */
    use IteratorAggregateTrait;

    /**
     * Add an item to the collection.
     *
     * @param Item $item The new item
     *
     * @return void
     *
     * @api
     */
    public function add(mixed $item): void
    {
        $this->data[] = $item;
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
     * Get a new collection with the same set of items.
     *
     * @return Collection<Item> The new collection with the same items
     *
     * @api
     */
    public function copy(): Collection
    {
        return new Collection($this->data);
    }

    /**
     * Get the item at the specified index.
     *
     * @param array-key $key The item's index
     *
     * @return ?Item The item or NULL if key is invalid
     *
     * @api
     */
    public function get(int|string $key): mixed
    {
        return $this->data[$key] ?? null;
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
     * Set the item at the specified index.
     *
     * @param array-key $key The new item's index
     * @param Item $item The new item
     *
     * @return void
     *
     * @api
     */
    public function set(int|string $key, mixed $item): void
    {
        $this->data[$key] = $item;
    }

    /**
     * Return array representation of collection.
     *
     * @return array<Item> Array of collection items
     *
     * @api
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Create a collection of items.
     *
     * @param array<Item> $items Initial set of items
     *
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->data = $items;
    }

    /**
     * Magic method to read collection items as properties.
     *
     * @param array-key $key The item's index
     *
     * @return ?Item The item or NULL if key is invalid
     */
    public function __get(int|string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Magic method to write collection items as properties.
     *
     * @param array-key $key The new item's index
     * @param Item $item The new item
     *
     * @return void
     */
    public function __set(int|string $key, mixed $item): void
    {
        $this->set($key, $item);
    }
}
