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

/**
 * A type-sensitive, ordered Set.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 * @implements \Countable
 * @implements \Iterator
 * @implements \Serializable
 */
class Set extends AbstractList
{
    /**
     * Add a single item.
     *
     * @param mixed $item The item to add
     * @param ?int $offset Optional offset to add, defaults to append
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function add(mixed $item, ?int $offset = null): void
    {
        if (is_null($offset)) {
            $this->append($item);
        } elseif (isset($item)) {
            if (!$this->isAllowedType($item)) {
                throw new InvalidArgumentException('Parameter 1 must be an allowed type, ' . get_debug_type($item) . ' given.');
            }
            array_splice($this->items, $offset, 0, [$item]);
            if ($offset >= 0) {
                if ($offset <= $this->position) {
                    ++$this->position;
                }
            } elseif ($offset < 0) {
                if (($offset + $this->count()) <= $this->position) {
                    ++$this->position;
                }
            }
        }
    }

    /**
     * Get a single item.
     *
     * @param ?int $offset Optional offset to peek, defaults to current
     *
     * @return mixed The item or NULL if empty
     */
    public function peek(?int $offset = null): mixed
    {
        if (is_null($offset)) {
            return $this->current();
        }
        $item = array_slice($this->items, $offset, 1);
        return $item[0] ?? null;
    }

    /**
     * Remove a single item.
     *
     * @param ?int $offset Optional offset to remove, defauls to current
     *
     * @return mixed The removed item or NULL if empty
     */
    public function remove(?int $offset = null): mixed
    {
        if (is_null($offset)) {
            $offset = $this->position;
        }
        $item = array_splice($this->items, $offset, 1);
        if (isset($item[0])) {
            if ($offset >= 0) {
                if ($offset <= $this->position) {
                    --$this->position;
                }
            } elseif ($offset < 0) {
                if (($offset + $this->count()) <= $this->position) {
                    --$this->position;
                }
            }
        }
        return $item[0] ?? null;
    }
}
