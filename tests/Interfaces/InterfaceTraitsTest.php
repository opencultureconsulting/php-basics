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

namespace OCC\Basics\Tests\Interfaces;

use ArrayIterator;
use OCC\Basics\Tests\Fixtures\InterfacesFixture;
use OCC\Basics\Tests\Fixtures\IteratorFixture;
use PHPUnit\Framework\TestCase;

use function count;
use function iterator_to_array;

final class InterfaceTraitsTest extends TestCase
{
    public function testArrayAccess(): void
    {
        $object = new InterfacesFixture();
        $object['a'] = 1;
        $object[] = 2;
        $this->assertTrue(isset($object['a']));
        $this->assertSame(1, $object['a']);
        $this->assertSame(2, $object[0]);
        $this->assertNull($object['missing']);
        unset($object['a']);
        $this->assertFalse(isset($object['a']));
    }

    public function testCountable(): void
    {
        $object = new InterfacesFixture();
        $this->assertCount(0, $object);
        $object[] = 1;
        $object[] = 2;
        $this->assertSame(2, count($object));
    }

    public function testIteratorAggregate(): void
    {
        $object = new InterfacesFixture();
        $object['a'] = 1;
        $object['b'] = 2;
        $this->assertInstanceOf(ArrayIterator::class, $object->getIterator());
        $this->assertSame(['a' => 1, 'b' => 2], iterator_to_array($object));
    }

    public function testIterator(): void
    {
        $iterator = new IteratorFixture(['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], iterator_to_array($iterator));
        // Iterating again must start from the beginning.
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], iterator_to_array($iterator));
    }

    public function testIteratorPrev(): void
    {
        $iterator = new IteratorFixture(['a' => 1, 'b' => 2]);
        $iterator->rewind();
        $iterator->next();
        $this->assertSame('b', $iterator->key());
        $iterator->prev();
        $this->assertSame('a', $iterator->key());
        $this->assertSame(1, $iterator->current());
    }

    public function testIteratorOverEmptyData(): void
    {
        $iterator = new IteratorFixture([]);
        $iterator->rewind();
        $this->assertFalse($iterator->valid());
    }

    public function testIteratorWithNullValues(): void
    {
        $iterator = new IteratorFixture(['a' => null, 'b' => false]);
        $this->assertSame(['a' => null, 'b' => false], iterator_to_array($iterator));
    }
}
