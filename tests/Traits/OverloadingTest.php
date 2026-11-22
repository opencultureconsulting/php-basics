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

namespace OCC\Basics\Tests\Traits;

use OCC\Basics\Tests\Fixtures\OverloadingFixture;
use PHPUnit\Framework\TestCase;

final class OverloadingTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $object = new OverloadingFixture();
        $object->foo = 'bar';
        $this->assertSame('bar', $object->foo);
    }

    public function testGetOfUnsetPropertyReturnsNull(): void
    {
        $object = new OverloadingFixture();
        $this->assertNull($object->foo);
    }

    public function testIssetAndUnset(): void
    {
        $object = new OverloadingFixture();
        $this->assertFalse(isset($object->foo));
        $object->foo = 'bar';
        $this->assertTrue(isset($object->foo));
        unset($object->foo);
        $this->assertFalse(isset($object->foo));
        $this->assertNull($object->foo);
    }

    public function testNullValueIsNotSet(): void
    {
        $object = new OverloadingFixture();
        $object->foo = null;
        $this->assertFalse(isset($object->foo));
    }
}
