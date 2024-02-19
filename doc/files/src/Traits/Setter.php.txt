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

use function method_exists;
use function property_exists;
use function sprintf;
use function ucfirst;

/**
 * Writes data to inaccessible properties by using magic methods.
 *
 * To make a `protected` or `private` property writable, provide a method named
 * `_magicSet{Property}()` which handles the writing. Replace `{Property}` in
 * the method's name with the name of the actual property (with an uppercase
 * first letter).
 *
 * > Example: If the property is named `$fooBar`, the "magic" method has to be
 * > `_magicSetFooBar()`. This method is then called when `$fooBar` is written
 * > to in a context where it normally would not be accessible.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 */
trait Setter
{
    /**
     * Write data to an inaccessible property.
     *
     * @param string $property The class property to set
     * @param mixed $value The new value of the property
     *
     * @return void
     *
     * @throws InvalidArgumentException if the property or setter method do not exist
     */
    public function __set(string $property, mixed $value): void
    {
        $method = '_magicSet' . ucfirst($property);
        if (property_exists(static::class, $property) && method_exists(static::class, $method)) {
            $this->$method($value);
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid property or missing setter method for property: %s->%s.',
                    static::class,
                    $property
                )
            );
        }
    }

    /**
     * Unset an inaccessible property.
     *
     * @param string $property The class property to unset
     *
     * @return void
     */
    public function __unset(string $property): void
    {
        try {
            $this->__set($property, null);
        } catch (InvalidArgumentException) {
        }
    }
}
