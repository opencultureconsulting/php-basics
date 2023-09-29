<?php
/**
 * Useful PHP Traits
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

namespace OCC\Traits;

/**
 * Reads data from inaccessible properties by using magic methods.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/traits
 * @access public
 */
trait Getter
{
    /**
     * Read data from an inaccessible property.
     *
     * @access public
     * @final
     *
     * @param string $property
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    final public function __get(string $property): mixed
    {
        $method = 'get' . ucfirst($property);
        if (
            property_exists(__CLASS__, $property)
            && method_exists(__CLASS__, $method)
        ) {
            return $this->$method();
        } else {
            throw new \InvalidArgumentException('Invalid property or missing getter method for property "' . __CLASS__ . '->' . $property . '".');
        }
    }

    /**
     * Check if an inaccessible property is set and not empty.
     *
     * @access public
     * @final
     *
     * @param string $property
     *
     * @return bool
     */
    final public function __isset(string $property): bool
    {
        try {
            $value = $this->__get($property);
        } catch (\InvalidArgumentException) {
            $value = null;
        }
        return !empty($value);
    }
}
