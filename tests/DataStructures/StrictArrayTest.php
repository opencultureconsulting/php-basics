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
use OCC\Basics\DataStructures\StrictArray;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function iterator_to_array;
use function serialize;
use function unserialize;

final class StrictArrayTest extends TestCase
{
    public function testAppendSyntax(): void
    {
        $array = new StrictArray(['int']);
        $array[] = 1;
        $array[] = 2;
        $array['key'] = 3;
        $array[] = 4;
        $this->assertSame([0 => 1, 1 => 2, 'key' => 3, 2 => 4], $array->toArray());
    }

    public function testAppendSyntaxChecksType(): void
    {
        $array = new StrictArray(['int']);
        $this->expectException(InvalidDataTypeException::class);
        $array[] = 'string';
    }

    public function testPushAndPop(): void
    {
        $array = new StrictArray();
        $array->push('a');
        $array->push('b');
        $this->assertSame('b', $array->pop());
        $this->assertSame('a', $array->pop());
        $this->assertTrue($array->isEmpty());
    }

    public function testUnshiftAndShift(): void
    {
        $array = new StrictArray();
        $array->unshift('a');
        $array->unshift('b');
        $this->assertSame(['b', 'a'], $array->toArray());
        $this->assertSame('b', $array->shift());
        $this->assertSame('a', $array->shift());
        $this->assertTrue($array->isEmpty());
    }

    public function testTopAndBottom(): void
    {
        $array = new StrictArray();
        $array['x'] = 'first';
        $array['y'] = 'last';
        $this->assertSame('first', $array->bottom());
        $this->assertSame('last', $array->top());
        $this->assertCount(2, $array);
    }

    public function testPushChecksType(): void
    {
        $array = new StrictArray(['int']);
        $this->expectException(InvalidDataTypeException::class);
        $array->push('string');
    }

    public function testUnshiftChecksType(): void
    {
        $array = new StrictArray(['int']);
        $this->expectException(InvalidDataTypeException::class);
        $array->unshift('string');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function emptyArrayMethodProvider(): iterable
    {
        yield 'bottom' => ['bottom'];
        yield 'pop' => ['pop'];
        yield 'shift' => ['shift'];
        yield 'top' => ['top'];
    }

    #[DataProvider('emptyArrayMethodProvider')]
    public function testMethodsThrowOnEmptyArray(string $method): void
    {
        $array = new StrictArray();
        $this->expectException(RuntimeException::class);
        $array->$method();
    }

    public function testIteration(): void
    {
        $array = new StrictArray();
        $array['a'] = 1;
        $array['b'] = 2;
        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($array));
    }

    public function testSerialization(): void
    {
        $array = new StrictArray(['int']);
        $array[] = 1;
        $array[] = 2;
        $copy = unserialize(serialize($array));
        $this->assertInstanceOf(StrictArray::class, $copy);
        $this->assertSame([1, 2], $copy->toArray());
        $this->assertSame(['int'], $copy->getAllowedTypes());
    }

    public function testFromArrayReturnsStrictArray(): void
    {
        $array = StrictArray::fromArray(['x' => 'a', 'y' => 'b']);
        $this->assertInstanceOf(StrictArray::class, $array);
        $this->assertSame([], $array->getAllowedTypes());
        $this->assertSame('b', $array->pop());
        $this->assertSame(['x' => 'a'], $array->toArray());
    }
}
