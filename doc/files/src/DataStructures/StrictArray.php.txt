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

use Iterator;
use OCC\Basics\Interfaces\IteratorTrait;

/**
 * A type-sensitive, traversable array.
 *
 * Holds items as key/value pairs where keys have to be valid array keys while
 * values can be of any type. To restrict allowed data types for items, provide
 * the constructor with an array of atomic types or fully qualified class
 * names.
 *
 * Internally it holds the items in the protected `$_data` array.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends StrictCollection<AllowedType>
 * @implements Iterator<AllowedType>
 */
class StrictArray extends StrictCollection implements Iterator
{
    /** @use IteratorTrait<AllowedType> */
    use IteratorTrait;
}
