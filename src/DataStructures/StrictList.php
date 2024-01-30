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

namespace OCC\Basics\DataStructures;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use RuntimeException;
use SplDoublyLinkedList;
use OCC\Basics\Traits\Getter;
use Serializable;

/**
 * A type-sensitive, taversable list.
 *
 * Extends [\SplDoublyLinkedList](https://www.php.net/spldoublylinkedlist) with
 * an option to specify the allowed data types for list values.
 *
 * @author Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
 * @package Basics\DataStructures
 *
 * @property-read string[] $allowedTypes The allowed data types for values.
 *
 * @api
 *
 * @template AllowedType of mixed
 * @extends SplDoublyLinkedList<AllowedType>
 * @implements ArrayAccess<int, AllowedType>
 * @implements Iterator<AllowedType>
 */
class StrictList extends SplDoublyLinkedList implements ArrayAccess, Countable, Iterator, Serializable
{
    use Getter;

    /**
     * The allowed data types for list values.
     *
     * @var string[]
     *
     * @internal
     */
    protected array $allowedTypes = [];

    /**
     * Add/insert a new value at the specified offset.
     *
     * @param int $offset The offset where the new value is to be inserted
     * @param AllowedType $value The new value for the offset
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function add(int $offset, mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        parent::add($offset, $value);
    }

    /**
     * Append values at the end of the list.
     *
     * @param AllowedType ...$values One or more values to append
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function append(mixed ...$values): void
    {
        foreach ($values as $count => $value) {
            if (!$this->isAllowedType($value)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        (int) $count + 1,
                        get_debug_type($value)
                    )
                );
            }
        }
        foreach ($values as $value) {
            parent::push($value);
        }
    }

    /**
     * Peek at the value at the beginning of the list.
     *
     * @return AllowedType The first value of the list
     *
     * @throws RuntimeException
     *
     * @api
     */
    public function bottom(): mixed
    {
        return parent::bottom();
    }

    /**
     * Get allowed data types for list values.
     *
     * @return string[] The list of allowed data types
     *
     * @api
     */
    public function getAllowedTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Get the mode of iteration.
     *
     * @return int The set of flags and modes of iteration
     *
     * @api
     */
    public function getIteratorMode(): int
    {
        return parent::getIteratorMode();
    }

    /**
     * Check if the value's data type is allowed on the list.
     *
     * @param AllowedType $value The value to check
     *
     * @return bool Whether the value's data type is allowed
     *
     * @api
     */
    public function isAllowedType(mixed $value): bool
    {
        if (count($this->allowedTypes) === 0) {
            return true;
        }
        foreach ($this->allowedTypes as $type) {
            $function = 'is_' . $type;
            if (function_exists($function) && $function($value)) {
                return true;
            }
            /** @var class-string */
            $fqcn = ltrim($type, '\\');
            if (is_object($value) && is_a($value, $fqcn)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Magic getter method for $this->allowedTypes.
     *
     * @return string[] The list of allowed data types
     *
     * @internal
     */
    protected function magicGetAllowedTypes(): array
    {
        return $this->getAllowedTypes();
    }

    /**
     * Set the value at the specified offset.
     *
     * @param ?int $offset The offset being set or NULL to append
     * @param AllowedType $value The new value for the offset
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @internal
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 2 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        /** @psalm-suppress PossiblyNullArgument */
        parent::offsetSet($offset, $value);
    }

    /**
     * Pops an value from the end of the list.
     *
     * @return AllowedType The value from the end of the list
     *
     * @throws RuntimeException
     *
     * @api
     */
    public function pop(): mixed
    {
        return parent::pop();
    }

    /**
     * Prepend values at the start of the list.
     *
     * @param AllowedType ...$values One or more values to prepend
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function prepend(mixed ...$values): void
    {
        foreach ($values as $count => $value) {
            if (!$this->isAllowedType($value)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Parameter %d must be an allowed type, %s given.',
                        (int) $count + 1,
                        get_debug_type($value)
                    )
                );
            }
        }
        foreach ($values as $value) {
            parent::unshift($value);
        }
    }

    /**
     * Push an value at the end of the list.
     *
     * @param AllowedType $value The value to push
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function push(mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        parent::push($value);
    }

    /**
     * Get string representation of $this.
     *
     * @return string The string representation
     *
     * @internal
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * Set allowed data types of list values.
     *
     * @param string[] $allowedTypes Allowed data types of values
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function setAllowedTypes(array $allowedTypes = []): void
    {
        if (array_sum(array_map('is_string', $allowedTypes)) !== count($allowedTypes)) {
            throw new InvalidArgumentException(
                'Allowed types must be array of strings or empty array.'
            );
        }
        $this->allowedTypes = $allowedTypes;
    }

    /**
     * Set the mode of iteration.
     *
     * @param int $mode The new iterator mode (0, 1, 2 or 3)
     *
     *                  There are two orthogonal sets of modes that can be set.
     *
     *                  The direction of iteration (either one or the other):
     *                  - StrictList::IT_MODE_FIFO (queue style)
     *                  - StrictList::IT_MODE_LIFO (stack style)
     *
     *                  The behavior of the iterator (either one or the other):
     *                  - StrictList::IT_MODE_DELETE (delete items)
     *                  - StrictList::IT_MODE_KEEP (keep items)
     *
     *                  The default mode is: IT_MODE_FIFO | IT_MODE_KEEP
     *
     * @return int The set of flags and modes of iteration
     *
     * @api
     */
    public function setIteratorMode(int $mode): int
    {
        return parent::setIteratorMode($mode);
    }

    /**
     * Shift an value from the beginning of the list.
     *
     * @return AllowedType The first value of the list
     *
     * @throws RuntimeException
     *
     * @api
     */
    public function shift(): mixed
    {
        return parent::shift();
    }

    /**
     * Peek at the value at the end of the list.
     *
     * @return AllowedType The last value of the list
     *
     * @throws RuntimeException
     *
     * @api
     */
    public function top(): mixed
    {
        return parent::top();
    }

    /**
     * Restore $this from string representation.
     *
     * @param string $data The string representation
     *
     * @return void
     *
     * @internal
     */
    public function unserialize($data): void
    {
        /** @var mixed[] $dataArray */
        $dataArray = unserialize($data);
        $this->__unserialize($dataArray);
    }

    /**
     * Prepend the list with an value.
     *
     * @param AllowedType $value The value to unshift
     *
     * @return void
     *
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function unshift(mixed $value): void
    {
        if (!$this->isAllowedType($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter 1 must be an allowed type, %s given.',
                    get_debug_type($value)
                )
            );
        }
        parent::unshift($value);
    }

    /**
     * Create a type-sensitive, traversable list of values.
     *
     * @param string[] $allowedTypes Allowed data types of values (optional)
     *
     *                               If empty, all types are allowed.
     *                               Possible values are:
     *                               - "array"
     *                               - "bool"
     *                               - "callable"
     *                               - "countable"
     *                               - "float" or "double"
     *                               - "int" or "integer" or "long"
     *                               - "iterable"
     *                               - "null"
     *                               - "numeric"
     *                               - "object" or FQCN
     *                               - "resource"
     *                               - "scalar"
     *                               - "string"
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $allowedTypes = [])
    {
        $this->setAllowedTypes($allowedTypes);
    }

    /**
     * Get debug information for $this.
     *
     * @return mixed[] The debug information
     *
     * @internal
     */
    public function __debugInfo(): array
    {
        return $this->__serialize();
    }

    /**
     * Get array representation of $this.
     *
     * @return mixed[] The array representation
     *
     * @internal
     */
    public function __serialize(): array
    {
        return [
            'StrictList::allowedTypes' => $this->allowedTypes,
            'SplDoublyLinkedList::dllist' => iterator_to_array($this),
            'SplDoublyLinkedList::flags' => $this->getIteratorMode()
        ];
    }

    /**
     * Restore $this from array representation.
     *
     * @param mixed[] $data The array representation
     *
     * @return void
     *
     * @internal
     *
     * @psalm-suppress MethodSignatureMismatch
     */
    public function __unserialize(array $data): void
    {
        /** @var string[] $allowedTypes */
        $allowedTypes = $data['StrictList::allowedTypes'];
        $this->setAllowedTypes($allowedTypes);
        /** @var array<int, AllowedType> $values */
        $values = $data['SplDoublyLinkedList::dllist'];
        $this->append(...$values);
        /** @var int $flags */
        $flags = $data['SplDoublyLinkedList::flags'];
        $this->setIteratorMode($flags);
    }
}
