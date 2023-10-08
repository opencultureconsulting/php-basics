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
 * Allows just a single instance of a class.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package opencultureconsulting/traits
 * @access public
 */
trait Singleton
{
    /**
     * Holds the singleton instance.
     *
     * @access private
     * @static
     *
     * @var self
     */
    private static ?self $singleton;

    /**
     * Get a singleton instance of this class.
     *
     * @access public
     * @final
     * @static
     *
     * @return self
     */
    final public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            $reflectionClass = new \ReflectionClass(__CLASS__);
            self::$instance = $reflectionClass->newInstanceArgs(func_get_args());
        }
        return self::$singleton;
    }

    /**
     * This is a singleton class, thus the constructor is private.
     * (Get an instance of this class by calling self::getInstance())
     *
     * @access private
     * @abstract
     */
    abstract private function __construct();

    /**
     * This is a singleton class, thus cloning is prohibited.
     *
     * @access private
     *
     * @return void
     */
    final private function __clone(): void
    {
    }
}
