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

namespace OCC\Basics\Traits;

/**
 * Overloads a class with writable magic properties.
 *
 * Internally it writes the protected `$_data` array whose keys are interpreted
 * as property names.
 *
 * > Example: `Foo->bar = 42;` will set `$_data['bar']` to `42`.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 */
trait OverloadingSetter
{
    /**
     * Holds the magically writable data.
     *
     * @var mixed[]
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Write data to an overloaded property.
     *
     * @param string $property The class property to set
     * @param mixed $value The new value of the property
     *
     * @return void
     */
    public function __set(string $property, mixed $value): void
    {
        $this->_data[$property] = $value;
    }

    /**
     * Unset an overloaded property.
     *
     * @param string $property The class property to unset
     *
     * @return void
     */
    public function __unset(string $property): void
    {
        unset($this->_data[$property]);
    }
}
