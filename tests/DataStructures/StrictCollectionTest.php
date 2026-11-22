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

use DomainException;
use InvalidArgumentException;
use OCC\Basics\DataStructures\Exceptions\InvalidDataTypeException;
use OCC\Basics\DataStructures\StrictArray;
use OCC\Basics\DataStructures\StrictCollection;
use OCC\Basics\DataStructures\StrictList;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use Serializable;
use stdClass;

use function serialize;
use function unserialize;

final class StrictCollectionTest extends TestCase
{
    public function testAddGetSetRemove(): void
    {
        $collection = new StrictCollection(['int']);
        $collection->add('a', 1);
        $collection->set('b', 2);
        $collection[5] = 3;
        $this->assertSame(1, $collection->get('a'));
        $this->assertSame(2, $collection['b']);
        $this->assertSame(3, $collection->get(5));
        $collection->set('a', 10);
        $this->assertSame(10, $collection->get('a'));
        $collection->remove('a');
        $this->assertFalse(isset($collection['a']));
        $this->assertSame(['b' => 2, 5 => 3], $collection->toArray());
    }

    public function testRejectsDisallowedTypes(): void
    {
        $collection = new StrictCollection(['int']);
        $this->expectException(InvalidDataTypeException::class);
        $this->expectExceptionMessage('Parameter 2 must be an allowed type, string given.');
        $collection['a'] = 'string';
    }

    public function testRejectsAppendWithoutKey(): void
    {
        $collection = new StrictCollection();
        $this->expectException(InvalidArgumentException::class);
        $collection[] = 1;
    }

    public function testRejectsInvalidKeyType(): void
    {
        $collection = new StrictCollection();
        $this->expectException(InvalidArgumentException::class);
        $collection->offsetSet(1.5, 1);
    }

    public function testGetOfMissingOffsetThrows(): void
    {
        $collection = new StrictCollection();
        $this->expectException(OutOfRangeException::class);
        $collection->get('missing');
    }

    public function testRemoveOfMissingOffsetThrows(): void
    {
        $collection = new StrictCollection();
        $this->expectException(OutOfRangeException::class);
        $collection->remove('missing');
    }

    public function testNullValues(): void
    {
        $collection = new StrictCollection(['null', 'int']);
        $collection['a'] = null;
        $this->assertNull($collection->get('a'));
        $this->assertCount(1, $collection);
        $collection->remove('a');
        $this->assertTrue($collection->isEmpty());
    }

    public function testCountClearAndIsEmpty(): void
    {
        $collection = new StrictCollection();
        $this->assertTrue($collection->isEmpty());
        $collection['a'] = 1;
        $collection['b'] = 2;
        $this->assertCount(2, $collection);
        $this->assertFalse($collection->isEmpty());
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
        $this->assertSame([], $collection->toArray());
    }

    public function testIsList(): void
    {
        $collection = new StrictCollection();
        $this->assertTrue($collection->isList());
        $collection[0] = 'a';
        $collection[1] = 'b';
        $this->assertTrue($collection->isList());
        $collection[3] = 'c';
        $this->assertFalse($collection->isList());
    }

    public function testToStrictList(): void
    {
        $collection = new StrictCollection(['string']);
        $collection[0] = 'a';
        $collection[1] = 'b';
        $list = $collection->toStrictList();
        $this->assertInstanceOf(StrictList::class, $list);
        $this->assertSame(['a', 'b'], $list->toArray());
        $this->assertSame(['string'], $list->getAllowedTypes());
    }

    public function testToStrictListRejectsNonLists(): void
    {
        $collection = new StrictCollection();
        $collection['a'] = 1;
        $this->expectException(DomainException::class);
        $collection->toStrictList();
    }

    public function testGetAllowedTypes(): void
    {
        $collection = new StrictCollection(['int', stdClass::class]);
        $this->assertSame(['int', stdClass::class], $collection->getAllowedTypes());
        $this->assertTrue($collection->isAllowedType('int'));
        $this->assertFalse($collection->isAllowedType('string'));
    }

    public function testSerialization(): void
    {
        $collection = new StrictCollection(['int']);
        $collection['a'] = 1;
        $collection[7] = 2;
        $copy = unserialize(serialize($collection));
        $this->assertInstanceOf(StrictCollection::class, $copy);
        $this->assertSame(['a' => 1, 7 => 2], $copy->toArray());
        $this->assertSame(['int'], $copy->getAllowedTypes());
        $this->expectException(InvalidDataTypeException::class);
        $copy['b'] = 'string';
    }

    public function testDoesNotImplementSerializableInterface(): void
    {
        $this->assertNotInstanceOf(Serializable::class, new StrictCollection());
        $this->assertNotInstanceOf(Serializable::class, new StrictArray());
    }

    public function testDebugInfo(): void
    {
        $collection = new StrictCollection(['int']);
        $collection['a'] = 1;
        $this->assertSame(
            ['StrictCollection::allowedTypes' => ['int'], 'StrictCollection::items' => ['a' => 1]],
            $collection->__debugInfo()
        );
    }

    public function testFromArray(): void
    {
        $collection = StrictCollection::fromArray(['a' => 1, 5 => null], ['?int']);
        $this->assertInstanceOf(StrictCollection::class, $collection);
        $this->assertSame(['a' => 1, 5 => null], $collection->toArray());
        $this->assertSame(['null', 'int'], $collection->getAllowedTypes());
    }

    public function testFromArrayChecksTypes(): void
    {
        $this->expectException(InvalidDataTypeException::class);
        StrictCollection::fromArray(['a' => 1, 'b' => 'two'], ['int']);
    }
}
