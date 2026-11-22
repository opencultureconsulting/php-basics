<?php

/**
 * PHP Basics
 *
 * Copyright (C) 2026 Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
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

namespace OCC\Basics\Tests\Fixtures;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use OCC\Basics\Interfaces\ArrayAccessTrait;
use OCC\Basics\Interfaces\CountableTrait;
use OCC\Basics\Interfaces\IteratorAggregateTrait;

/**
 * A class using the ArrayAccess, Countable and IteratorAggregate traits.
 *
 * @implements ArrayAccess<array-key, mixed>
 * @implements IteratorAggregate<array-key, mixed>
 */
final class InterfacesFixture implements ArrayAccess, Countable, IteratorAggregate
{
    use ArrayAccessTrait;
    use CountableTrait;
    use IteratorAggregateTrait;
}
