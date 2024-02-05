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
    private static array $singleton = [];

    /**
     * Get a singleton instance of this class.
     *
     * @param mixed ...$args Constructor parameters
     *
     * @return static The singleton instance
     *
     * @api
     */
    final public static function getInstance(mixed ...$args): static
    {
        if (!isset(static::$singleton[static::class])) {
            static::$singleton[static::class] = new static(...$args);
        }
        return static::$singleton[static::class];
    }

    /**
     * This is a singleton class, thus the constructor is private.
     *
     * Usage: Get an instance of this class by calling static::getInstance()
     *
     * @return void
     */
    abstract private function __construct();

    /**
     * This is a singleton class, thus cloning is prohibited.
     *
     * @return void
     *
     * @throws LogicException
     *
     * @internal
     */
    final public function __clone()
    {
        throw new LogicException('Cloning a singleton is prohibited.');
    }
}
