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

declare(strict_types=1);

namespace OCC\Traits;

/**
 * Allows just a single instance of the class using this trait.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/traits
 */
trait Singleton
{
    /**
     * Holds the singleton instances.
     */
    private static ?self $singleton;

    /**
     * Get a singleton instance of this class.
     */
    final public static function getInstance(): self
    {
        if (!isset(static::$singleton)) {
            $reflectionClass = new \ReflectionClass(get_called_class());
            static::$singleton = $reflectionClass->newInstanceArgs(func_get_args());
        }
        return static::$singleton;
    }

    /**
     * This is a singleton class, thus the constructor is private.
     * (Get an instance of this class by calling self::getInstance())
     */
    abstract private function __construct();

    /**
     * This is a singleton class, thus cloning is prohibited.
     */
    final private function __clone(): void
    {
    }
}
