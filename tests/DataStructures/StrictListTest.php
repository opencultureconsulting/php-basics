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
use OCC\Basics\DataStructures\StrictCollection;
use OCC\Basics\DataStructures\StrictList;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use SplDoublyLinkedList;

use function iterator_to_array;
use function serialize;
use function str_replace;
use function unserialize;

final class StrictListTest extends TestCase
{
    public function testAppendAndPrepend(): void
    {
        $list = new StrictList(['int']);
        $list->append(3, 4);
        $list->prepend(1, 2);
        $this->assertSame([1, 2, 3, 4], $list->toArray());
    }

    public function testAppendIsAtomic(): void
    {
        $list = new StrictList(['int']);
        try {
            $list->append(1, 'two', 3);
            $this->fail('No exception thrown.');
        } catch (InvalidDataTypeException $exception) {
            $this->assertStringContainsString('Parameter 2', $exception->getMessage());
        }
        $this->assertTrue($list->isEmpty());
    }

    public function testPrependIsAtomic(): void
    {
        $list = new StrictList(['int']);
        try {
            $list->prepend(1, 'two');
            $this->fail('No exception thrown.');
        } catch (InvalidDataTypeException) {
        }
        $this->assertTrue($list->isEmpty());
    }

    public function testPushAndUnshiftCheckType(): void
    {
        $list = new StrictList(['int']);
        $list->push(2);
        $list->unshift(1);
        $this->assertSame([1, 2], $list->toArray());
        $this->expectException(InvalidDataTypeException::class);
        $list->push('string');
    }

    public function testUnshiftChecksType(): void
    {
        $list = new StrictList(['int']);
        $this->expectException(InvalidDataTypeException::class);
        $list->unshift('string');
    }

    public function testGetSetRemove(): void
    {
        $list = new StrictList(['string']);
        $list->append('a', 'b', 'c');
        $this->assertSame('b', $list->get(1));
        $list->set(1, 'B');
        $this->assertSame(['a', 'B', 'c'], $list->toArray());
        $list->remove(0);
        $this->assertSame(['B', 'c'], $list->toArray());
    }

    public function testAddInsertsAtOffset(): void
    {
        $list = new StrictList(['string']);
        $list->append('a', 'c');
        $list->add(1, 'b');
        $this->assertSame(['a', 'b', 'c'], $list->toArray());
        $list->add(3, 'd');
        $this->assertSame(['a', 'b', 'c', 'd'], $list->toArray());
    }

    public function testAddChecksType(): void
    {
        $list = new StrictList(['string']);
        $this->expectException(InvalidDataTypeException::class);
        $list->add(0, 1);
    }

    public function testArrayAccess(): void
    {
        $list = new StrictList(['int']);
        $list[] = 1;
        $list[] = 2;
        $list[0] = 10;
        $this->assertSame(10, $list[0]);
        $this->assertTrue(isset($list[1]));
        $this->assertFalse(isset($list[2]));
        $this->expectException(InvalidDataTypeException::class);
        $list[] = 'string';
    }

    public function testGetOutOfRangeThrows(): void
    {
        $list = new StrictList();
        $this->expectException(OutOfRangeException::class);
        $list->get(0);
    }

    public function testClear(): void
    {
        $list = new StrictList();
        $list->append(1, 2, 3);
        $list->clear();
        $this->assertTrue($list->isEmpty());
        $this->assertCount(0, $list);
    }

    public function testIsList(): void
    {
        $this->assertTrue((new StrictList())->isList());
    }

    public function testIteration(): void
    {
        $list = new StrictList();
        $list->append('a', 'b');
        $this->assertSame(['a', 'b'], iterator_to_array($list));
    }

    public function testToStrictCollection(): void
    {
        $list = new StrictList(['int']);
        $list->append(1, 2);
        $collection = $list->toStrictCollection();
        $this->assertInstanceOf(StrictCollection::class, $collection);
        $this->assertSame([1, 2], $collection->toArray());
        $this->assertSame(['int'], $collection->getAllowedTypes());
    }

    public function testSerialization(): void
    {
        $list = new StrictList(['int']);
        $list->append(1, 2, 3);
        $list->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
        $copy = unserialize(serialize($list));
        $this->assertInstanceOf(StrictList::class, $copy);
        $this->assertSame(['int'], $copy->getAllowedTypes());
        $this->assertSame(SplDoublyLinkedList::IT_MODE_LIFO, $copy->getIteratorMode());
        $this->assertSame([3, 2, 1], $copy->toArray());
    }

    public function testLegacySerializableInterface(): void
    {
        $list = new StrictList(['int']);
        $list->append(1, 2);
        $copy = new StrictList();
        $copy->unserialize($list->serialize());
        $this->assertSame([1, 2], $copy->toArray());
        $this->assertSame(['int'], $copy->getAllowedTypes());
    }

    public function testLegacySerializableInterfaceChecksTypes(): void
    {
        // SplDoublyLinkedList implements Serializable, so the type-checking
        // override must not be bypassed by calling unserialize() directly.
        $list = new StrictList();
        $list->append(1, 'two');
        $payload = $list->serialize();
        $payload = str_replace('a:0:{}', 'a:1:{i:0;s:3:"int";}', $payload);
        $copy = new StrictList();
        $this->expectException(InvalidDataTypeException::class);
        $copy->unserialize($payload);
    }

    public function testFromArray(): void
    {
        $list = StrictList::fromArray(['a' => 1, 'b' => 2, 'c' => 3], ['int']);
        $this->assertInstanceOf(StrictList::class, $list);
        $this->assertSame([1, 2, 3], $list->toArray());
        $this->assertSame(['int'], $list->getAllowedTypes());
    }

    public function testFromArrayChecksTypes(): void
    {
        $this->expectException(InvalidDataTypeException::class);
        $this->expectExceptionMessage('Parameter 1 must be an allowed type, string given.');
        StrictList::fromArray([1, 'two'], ['int']);
    }

    public function testUnionTypes(): void
    {
        $list = new StrictList(['int|string']);
        $list->append(1, 'two');
        $this->assertSame([1, 'two'], $list->toArray());
        $this->expectException(InvalidDataTypeException::class);
        $list->push(3.0);
    }
}
