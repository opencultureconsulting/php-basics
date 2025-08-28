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

namespace OCC\Basics\Interfaces;

use function current;
use function is_null;
use function key;
use function next;
use function prev;
use function reset;

/**
 * A generic implementation of the Iterator interface.
 *
 * Internally it iterates over the protected `$_data` array.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Interfaces
 *
 * @api
 *
 * @template TKey of array-key
 * @template TValue of mixed
 *
 * @phpstan-require-implements \Iterator<TKey, TValue>
 */
trait IteratorTrait
{
    /**
     * Holds the iterable data.
     *
     * @var array<TKey, TValue>
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Return the current item.
     *
     * @return TValue|false The current item or FALSE if invalid
     *
     * @api
     */
    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return current($this->_data);
    }

    /**
     * Return the current key.
     *
     * @return ?TKey The current key or NULL if invalid
     *
     * @api
     */
    #[\ReturnTypeWillChange]
    public function key(): mixed
    {
        return key($this->_data);
    }

    /**
     * Move forward to next item.
     *
     * @return void
     *
     * @api
     */
    public function next(): void
    {
        next($this->_data);
    }

    /**
     * Move back to previous item.
     *
     * @return void
     *
     * @api
     */
    public function prev(): void
    {
        prev($this->_data);
    }

    /**
     * Rewind the iterator to the first item.
     *
     * @return void
     *
     * @api
     */
    public function rewind(): void
    {
        reset($this->_data);
    }

    /**
     * Check if current position is valid.
     *
     * @return bool Whether the current position is valid
     *
     * @api
     *
     * @phpstan-assert-if-true TValue $this->current()
     * @phpstan-assert-if-true !null $this->key()
     */
    public function valid(): bool
    {
        return !is_null($this->key());
    }
}
