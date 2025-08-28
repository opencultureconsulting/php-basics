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

use ArrayIterator;

/**
 * A generic implementation of the IteratorAggregate interface.
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
 * @phpstan-require-implements \IteratorAggregate<TKey, TValue>
 */
trait IteratorAggregateTrait
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
     * Retrieve an external iterator.
     *
     * @return ArrayIterator<TKey, TValue> New iterator for the data array
     *
     * @api
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->_data);
    }
}
