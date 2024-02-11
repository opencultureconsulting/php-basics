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

use Countable;

/**
 * A generic implementation of the Countable interface.
 *
 * Internally it counts the values of the protected `$_data` array.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\InterfaceTraits
 *
 * @api
 *
 * @template TValue of mixed
 * @implements Countable
 * @phpstan-require-implements Countable
 */
trait CountableTrait
{
    /**
     * Holds the countable data.
     *
     * @var TValue[]
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Count the data items.
     *
     * @return int<0, max> The number of data items
     *
     * @api
     */
    public function count(): int
    {
        return count($this->_data);
    }
}
