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

use LogicException;

/**
 * Allows just a single instance of the class using this trait.
 *
 * Get the singleton by calling the static method `getInstance()`.
 *
 * If there is no object yet, the constructor is called with the same arguments
 * as `getInstance()`. Any later call will just return the already instantiated
 * object (irrespective of the given arguments).
 *
 * In order for this to work as expected, the constructor has to be implemented
 * as `private` to prevent direct instantiation of the class.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 */
trait Singleton
{
    /**
     * Holds the singleton instance.
     *
     * @var array<class-string, static>
     *
     * @internal
     */
    private static array $_singleton = [];

    /**
     * Get a singleton instance of this class.
     *
     * @param mixed ...$args Constructor arguments
     *
     * @return static The singleton instance
     *
     * @api
     */
    final public static function getInstance(mixed ...$args): static
    {
        if (!isset(static::$_singleton[static::class])) {
            static::$_singleton[static::class] = new static(...$args);
        }
        return static::$_singleton[static::class];
    }

    /**
     * This is a singleton class, thus the constructor is private.
     *
     * @return void
     *
     * @see Singleton::getInstance() to get an singleton object of the class
     */
    abstract private function __construct();

    /**
     * This is a singleton class, thus cloning is prohibited.
     *
     * @return void
     *
     * @throws LogicException when trying to clone the singleton object
     *
     * @internal
     */
    final public function __clone()
    {
        throw new LogicException('Cloning a singleton is prohibited.');
    }
}
