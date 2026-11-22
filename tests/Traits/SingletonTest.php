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

use Error;
use LogicException;
use OCC\Basics\Tests\Fixtures\SingletonChildFixture;
use OCC\Basics\Tests\Fixtures\SingletonFixture;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

final class SingletonTest extends TestCase
{
    #[RunInSeparateProcess]
    public function testGetInstanceReturnsSameInstance(): void
    {
        $first = SingletonFixture::getInstance();
        $second = SingletonFixture::getInstance();
        $this->assertSame($first, $second);
        $this->assertSame('default', $first->value);
    }

    #[RunInSeparateProcess]
    public function testGetInstancePassesArgumentsToConstructor(): void
    {
        $instance = SingletonFixture::getInstance('custom');
        $this->assertSame('custom', $instance->value);
    }

    #[RunInSeparateProcess]
    public function testSubclassesGetTheirOwnInstance(): void
    {
        $parent = SingletonFixture::getInstance();
        $child = SingletonChildFixture::getInstance();
        $this->assertNotSame($parent, $child);
        $this->assertInstanceOf(SingletonChildFixture::class, $child);
        $this->assertSame($child, SingletonChildFixture::getInstance());
    }

    public function testConstructorIsNotPublic(): void
    {
        $this->expectException(Error::class);
        /** @phpstan-ignore new.private */
        new SingletonFixture();
    }

    #[RunInSeparateProcess]
    public function testCloningIsProhibited(): void
    {
        $instance = SingletonFixture::getInstance();
        $this->expectException(LogicException::class);
        clone $instance;
    }
}
