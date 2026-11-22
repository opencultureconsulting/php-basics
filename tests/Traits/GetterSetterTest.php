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

use InvalidArgumentException;
use OCC\Basics\Tests\Fixtures\GetterSetterFixture;
use PHPUnit\Framework\TestCase;

final class GetterSetterTest extends TestCase
{
    public function testGetCallsMagicGetter(): void
    {
        $object = new GetterSetterFixture();
        $this->assertSame('initial', $object->name);
        $this->assertSame('read only', $object->readOnly);
    }

    public function testSetCallsMagicSetter(): void
    {
        $object = new GetterSetterFixture();
        $object->name = 'changed';
        $this->assertSame('CHANGED', $object->name);
        $object->writeOnly = 'written';
        $this->assertSame('written', $object->getWriteOnly());
    }

    public function testGetWithoutMagicGetterThrows(): void
    {
        $object = new GetterSetterFixture();
        $this->expectException(InvalidArgumentException::class);
        $object->writeOnly;
    }

    public function testGetOfPropertyWithoutMagicMethodsThrows(): void
    {
        $object = new GetterSetterFixture();
        $this->expectException(InvalidArgumentException::class);
        $object->hidden;
    }

    public function testGetOfUndefinedPropertyThrows(): void
    {
        $object = new GetterSetterFixture();
        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore property.notFound */
        $object->undefined;
    }

    public function testSetWithoutMagicSetterThrows(): void
    {
        $object = new GetterSetterFixture();
        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore property.readOnlyByPhpDocAssign */
        $object->readOnly = 'changed';
    }

    public function testSetOfUndefinedPropertyThrows(): void
    {
        $object = new GetterSetterFixture();
        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore property.notFound */
        $object->undefined = 'value';
    }

    public function testIsset(): void
    {
        $object = new GetterSetterFixture();
        $this->assertTrue(isset($object->name));
        $this->assertTrue(isset($object->readOnly));
        $this->assertFalse(isset($object->writeOnly));
        $this->assertFalse(isset($object->hidden));
        /** @phpstan-ignore property.notFound */
        $this->assertFalse(isset($object->undefined));
        $object->setReadOnlyToNull();
        $this->assertFalse(isset($object->readOnly));
    }

    public function testUnsetCallsSetterWithNull(): void
    {
        $object = new GetterSetterFixture();
        unset($object->name);
        $this->assertSame('', $object->name);
    }

    public function testUnsetOfInvalidPropertyIsIgnored(): void
    {
        $object = new GetterSetterFixture();
        /** @phpstan-ignore property.notFound */
        unset($object->undefined);
        unset($object->readOnly);
        $this->assertSame('read only', $object->readOnly);
    }
}
