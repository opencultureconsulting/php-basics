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

use function array_map;
use function array_sum;
use function array_values;
use function count;
use function function_exists;
use function in_array;
use function is_a;
use function is_array;
use function is_bool;
use function is_callable;
use function is_countable;
use function is_double;
use function is_float;
use function is_int;
use function is_integer;
use function is_iterable;
use function is_long;
use function is_null;
use function is_numeric;
use function is_resource;
use function is_scalar;
use function is_string;
use function is_object;
use function ltrim;

/**
 * A generic data type checker.
 *
 * This allows to set a list of allowed atomic data types and fully qualified
 * class names. It also provides a method to check if a value's data type
 * matches at least one of these types.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 *
 * @api
 */
trait TypeChecker
{
    /**
     * The allowed data types.
     *
     * @var string[]
     *
     * @internal
     */
    protected array $_allowedTypes = [];

    /**
     * Get allowed data types.
     *
     * @return string[] The list of allowed data types
     *
     * @api
     */
    public function getAllowedTypes(): array
    {
        return $this->_allowedTypes;
    }

    /**
     * Check if a value's data type is allowed.
     *
     * @param mixed $value The value to check
     *
     * @return bool Whether the value's data type is allowed
     *
     * @api
     */
    public function hasAllowedType(mixed $value): bool
    {
        if (count($this->getAllowedTypes()) === 0) {
            return true;
        }
        foreach ($this->getAllowedTypes() as $type) {
            $function = 'is_' . $type;
            if (function_exists($function) && $function($value)) {
                return true;
            }
            /** @var class-string $fqcn */
            $fqcn = ltrim($type, '\\');
            if (is_object($value) && is_a($value, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a data type is allowed.
     *
     * @param string $type The type to check
     *
     * @return bool Whether the data type is allowed
     *
     * @api
     */
    public function isAllowedType(string $type): bool
    {
        return in_array($type, $this->getAllowedTypes(), true);
    }

    /**
     * Set allowed data types.
     *
     * @param string[] $allowedTypes Allowed data types
     *
     * @return void
     *
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string
     *
     * @api
     */
    public function setAllowedTypes(array $allowedTypes = []): void
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        $this->_allowedTypes = array_values($allowedTypes);
    }
}
