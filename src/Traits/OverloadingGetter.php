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
 * Overloads a class with readable magic properties.
 *
 * Internally it reads the protected `$_data` array whose keys are interpreted
 * as property names.
 *
 * > Example: Reading `Foo->bar` will return the value of `$_data['bar']`.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 */
trait OverloadingGetter
{
    /**
     * Holds the magically readable data.
     *
     * @var mixed[]
     *
     * @internal
     */
    protected array $_data = [];

    /**
     * Read data from an overloaded property.
     *
     * @param string $property The class property to get
     *
     * @return mixed The property's current value or NULL if not set
     */
    public function __get(string $property): mixed
    {
        return $this->_data[$property] ?? null;
    }

    /**
     * Check if an overloaded property is set.
     *
     * @param string $property The class property to check
     *
     * @return bool Whether the class property is set
     */
    public function __isset(string $property): bool
    {
        return isset($this->_data[$property]);
    }
}
