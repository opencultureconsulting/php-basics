<?php

/**
 * PHP Basics
 *
 * Copyright (C) 2026 Sebastian Meyer <sebastian.meyer@opencultureconsulting.com>
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

namespace OCC\Basics\Tests\DataStructures;

use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\DataStructures\StrictStack;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function iterator_to_array;
use function serialize;
use function unserialize;

final class StrictStackTest extends TestCase
{
    public function testLastInFirstOut(): void
    {
        $stack = new StrictStack(['string']);
        $stack->stack('a');
        $stack->stack('b');
        $stack->stack('c');
        $this->assertSame(['c', 'b', 'a'], iterator_to_array($stack, false));
        $this->assertSame('c', $stack->unstack());
        $this->assertSame('b', $stack->unstack());
        $this->assertCount(1, $stack);
    }

    public function testStackChecksType(): void
    {
        $stack = new StrictStack(['string']);
        $this->expectException(InvalidDataTypeException::class);
        $stack->stack(1);
    }

    public function testUnstackOnEmptyStackThrows(): void
    {
        $stack = new StrictStack();
        $this->expectException(RuntimeException::class);
        $stack->unstack();
    }

    public function testSerialization(): void
    {
        $stack = new StrictStack(['string']);
        $stack->stack('a');
        $stack->stack('b');
        $copy = unserialize(serialize($stack));
        $this->assertInstanceOf(StrictStack::class, $copy);
        $this->assertSame(['string'], $copy->getAllowedTypes());
        $this->assertSame('b', $copy->unstack());
        $this->assertSame('a', $copy->unstack());
    }

    public function testFromArray(): void
    {
        $stack = StrictStack::fromArray(['a', 'b'], ['string']);
        $this->assertInstanceOf(StrictStack::class, $stack);
        $this->assertSame('b', $stack->unstack());
        $this->assertSame('a', $stack->unstack());
    }
}
