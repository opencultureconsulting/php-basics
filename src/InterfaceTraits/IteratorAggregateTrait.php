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

namespace OCC\Basics\InterfaceTraits;

use ArrayIterator;
use IteratorAggregate;

/**
 * A generic implementation of the IteratorAggregate interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\InterfaceTraits
 *
 * @template TKey of int|string
 * @template TValue of mixed
 * @implements IteratorAggregate<TKey, TValue>
 * @phpstan-require-implements IteratorAggregate
 */
trait IteratorAggregateTrait
{
    /**
     * Holds the iterable data.
     *
     * @var array<TKey, TValue>
     */
    protected array $data = [];

    /**
     * Retrieve an external iterator.
     *
     * @return ArrayIterator<TKey, TValue> New array iterator for data array
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }
}
