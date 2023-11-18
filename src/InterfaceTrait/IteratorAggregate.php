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

namespace OCC\Basics\InterfaceTrait;

use ArrayIterator;

/**
 * A generic implementation of the IteratorAggregate interface.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 *
 * @implements \IteratorAggregate
 */
trait IteratorAggregate /* implements \IteratorAggregate */
{
    /**
     * Holds the iterable data.
     */
    private array $data = [];

    /**
     * Retrieve an external iterator.
     * @see \IteratorAggregate::getIterator()
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }
}
