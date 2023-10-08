<?php declare(strict_types=1);
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
 * Writes data to inaccessible properties by using magic methods.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/traits
 * @access public
 */
trait Setter
{
    /**
     * Write data to an inaccessible property.
     *
     * @access public
     * @final
     *
     * @param string $property
     * @param mixed $value
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    final public function __set(string $property, mixed $value): void
    {
        $method = 'set' . ucfirst($property);
        if (
            property_exists(__CLASS__, $property)
            && method_exists(__CLASS__, $method)
        ) {
            $this->$method($value);
        } else {
            throw new \InvalidArgumentException('Invalid property or missing setter method for property "' . __CLASS__ . '->' . $property . '".');
        }
    }

    /**
     * Unset an inaccessible property.
     *
     * @access public
     * @final
     *
     * @param string $property
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    final public function __unset(string $property): void
    {
        try {
            $this->__set($property, null);
        } catch (\InvalidArgumentException) {}
    }
}
