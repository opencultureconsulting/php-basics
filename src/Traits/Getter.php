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

namespace OCC\Basics\Traits;

use InvalidArgumentException;

/**
 * Reads data from inaccessible properties by using magic methods.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/basics
 */
trait Getter
{
    /**
     * Read data from an inaccessible property.
     *
     * @param string $property The class property to get
     *
     * @return mixed The class property's current value
     *
     * @throws InvalidArgumentException
     */
    public function __get(string $property): mixed
    {
        $method = 'magicGet' . ucfirst($property);
        if (
            property_exists(get_called_class(), $property)
            && method_exists(get_called_class(), $method)
        ) {
            return $this->$method();
        } else {
            throw new InvalidArgumentException('Invalid property or missing getter method for property: ' . get_called_class() . '->' . $property . '.');
        }
    }

    /**
     * Check if an inaccessible property is set and not empty.
     *
     * @param string $property The class property to check
     *
     * @return bool Whether the class property is set and not empty
     */
    public function __isset(string $property): bool
    {
        try {
            $value = $this->__get($property);
        } catch (InvalidArgumentException) {
            $value = null;
        } finally {
            return !empty($value);
        }
    }
}
