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
use function array_unique;
use function array_values;
use function count;
use function explode;
use function in_array;
use function is_array;
use function is_bool;
use function is_callable;
use function is_countable;
use function is_float;
use function is_int;
use function is_iterable;
use function is_null;
use function is_numeric;
use function is_object;
use function is_resource;
use function is_scalar;
use function is_string;
use function ltrim;
use function str_starts_with;
use function substr;
use function trim;

/**
 * A generic data type checker.
 *
 * This allows to set a list of allowed atomic data types and fully qualified
 * class names. It also provides a method to check if a value's data type
 * matches at least one of these types.
 *
 * The following lowercase keywords are recognized as atomic data types:
 * `array`, `bool`, `callable`, `countable`, `double`, `float`, `int`,
 * `integer`, `iterable`, `long`, `null`, `numeric`, `object`, `resource`,
 * `scalar` and `string`. Any other value is treated as the name of a class
 * or interface the value has to be an instance of. Additionally, `mixed`
 * allows any value.
 *
 * Like in PHP's own type declarations, union types (e.g. `int|string`) and
 * nullable types (e.g. `?int`) are supported. They are split into separate
 * types when set (e.g. `?int` becomes `null` and `int`).
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\Traits
 *
 * @api
 *
 * @template AllowedType of mixed
 * @template AllowedTypes of array<string|class-string<AllowedType>>
 */
trait TypeChecker
{
    /**
     * The allowed data types.
     *
     * @var AllowedTypes
     *
     * @internal
     */
    protected array $_allowedTypes = [];

    /**
     * Get allowed data types.
     *
     * @return AllowedTypes The list of allowed data types
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
        if (count($this->_allowedTypes) === 0) {
            return true;
        }
        foreach ($this->_allowedTypes as $type) {
            $fqcn = ltrim($type, '\\');
            $isAllowed = match ($type) {
                'array' => is_array($value),
                'bool' => is_bool($value),
                'callable' => is_callable($value),
                'countable' => is_countable($value),
                'double', 'float' => is_float($value),
                'int', 'integer', 'long' => is_int($value),
                'iterable' => is_iterable($value),
                'mixed' => true,
                'null' => is_null($value),
                'numeric' => is_numeric($value),
                'object' => is_object($value),
                'resource' => is_resource($value),
                'scalar' => is_scalar($value),
                'string' => is_string($value),
                default => $value instanceof $fqcn
            };
            if ($isAllowed) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a data type is allowed.
     *
     * For union and nullable types, all of their types have to be allowed.
     *
     * @param string $type The type to check
     *
     * @return bool Whether the data type is allowed
     *
     * @throws InvalidArgumentException if `$type` contains an empty type
     *
     * @api
     */
    public function isAllowedType(string $type): bool
    {
        foreach ($this->normalizeTypes([$type]) as $atomicType) {
            if (!in_array($atomicType, $this->_allowedTypes, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Set allowed data types.
     *
     * @param AllowedTypes $allowedTypes Allowed data types
     *
     * @return void
     *
     * @throws InvalidArgumentException if any value of `$allowedTypes` is not a string or contains an empty type
     *
     * @api
     */
    public function setAllowedTypes(array $allowedTypes = []): void
    {
        /** @psalm-suppress RedundantCondition */
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        /** @var AllowedTypes $normalizedTypes */
        $normalizedTypes = $this->normalizeTypes($allowedTypes);
        $this->_allowedTypes = $normalizedTypes;
    }

    /**
     * Split union and nullable types into their atomic types.
     *
     * @param string[] $types The types to normalize
     *
     * @return list<string> The atomic types without duplicates
     *
     * @throws InvalidArgumentException if any type is empty
     *
     * @internal
     */
    private function normalizeTypes(array $types): array
    {
        $atomicTypes = [];
        foreach ($types as $type) {
            foreach (explode('|', $type) as $atomicType) {
                $atomicType = trim($atomicType);
                if (str_starts_with($atomicType, '?')) {
                    $atomicTypes[] = 'null';
                    $atomicType = trim(substr($atomicType, 1));
                }
                if ($atomicType === '') {
                    throw new InvalidArgumentException(
                        'Allowed types must not be empty.'
                    );
                }
                $atomicTypes[] = $atomicType;
            }
        }
        return array_values(array_unique($atomicTypes));
    }
}
