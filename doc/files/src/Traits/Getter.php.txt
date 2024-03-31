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

use InvalidArgumentException;

use function boolval;
use function method_exists;
use function property_exists;
use function sprintf;
use function ucfirst;

/**
 * Reads data from inaccessible properties by using magic methods.
 *
 * To make a `protected` or `private` property readable, provide a method named
 * `_magicGet{Property}()` which handles the reading. Replace `{Property}` in
 * the method's name with the name of the actual property (with an uppercase
 * first letter).
 *
 * > Example: If the property is named `$fooBar`, the "magic" method has to be
 * > `_magicGetFooBar()`. This method is then called when `$fooBar` is read in
 * > a context where it normally would not be accessible.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
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
     * @throws InvalidArgumentException if the property or getter method do not exist
     */
    public function __get(string $property): mixed
    {
        $method = '_magicGet' . ucfirst($property);
        if (property_exists(static::class, $property) && method_exists(static::class, $method)) {
            return $this->$method();
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid property or missing getter method for property: %s::$%s.',
                    static::class,
                    $property
                )
            );
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
            /** @var mixed $value */
            $value = $this->__get($property);
        } catch (InvalidArgumentException) {
            $value = null;
        } finally {
            return boolval($value ?? null) !== false;
        }
    }
}
