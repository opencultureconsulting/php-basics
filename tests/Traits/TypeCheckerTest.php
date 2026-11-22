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

use ArrayObject;
use Countable;
use InvalidArgumentException;
use OCC\Basics\Tests\Fixtures\File;
use OCC\Basics\Tests\Fixtures\TypeCheckerFixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function fclose;
use function fopen;

final class TypeCheckerTest extends TestCase
{
    public function testAllowsEverythingWithoutRestrictions(): void
    {
        $checker = new TypeCheckerFixture();
        $this->assertSame([], $checker->getAllowedTypes());
        $this->assertTrue($checker->hasAllowedType(null));
        $this->assertTrue($checker->hasAllowedType(1));
        $this->assertTrue($checker->hasAllowedType(new stdClass()));
    }

    /**
     * @return iterable<string, array{string, mixed, bool}>
     */
    public static function atomicTypeProvider(): iterable
    {
        yield 'array accepts array' => ['array', [], true];
        yield 'array rejects string' => ['array', 'a', false];
        yield 'bool accepts bool' => ['bool', false, true];
        yield 'bool rejects int' => ['bool', 0, false];
        yield 'callable accepts closure' => ['callable', static fn () => null, true];
        yield 'callable rejects int' => ['callable', 1, false];
        yield 'countable accepts array' => ['countable', [1], true];
        yield 'countable accepts Countable' => ['countable', new ArrayObject(), true];
        yield 'countable rejects string' => ['countable', 'abc', false];
        yield 'double accepts float' => ['double', 1.5, true];
        yield 'float accepts float' => ['float', 1.5, true];
        yield 'float rejects int' => ['float', 1, false];
        yield 'int accepts int' => ['int', 1, true];
        yield 'int rejects numeric string' => ['int', '1', false];
        yield 'integer accepts int' => ['integer', 1, true];
        yield 'long accepts int' => ['long', 1, true];
        yield 'iterable accepts array' => ['iterable', [], true];
        yield 'iterable accepts Traversable' => ['iterable', new ArrayObject(), true];
        yield 'iterable rejects object' => ['iterable', new stdClass(), false];
        yield 'null accepts null' => ['null', null, true];
        yield 'null rejects empty string' => ['null', '', false];
        yield 'numeric accepts numeric string' => ['numeric', '1.5', true];
        yield 'numeric rejects string' => ['numeric', 'abc', false];
        yield 'object accepts object' => ['object', new stdClass(), true];
        yield 'object rejects array' => ['object', [], false];
        yield 'scalar accepts string' => ['scalar', 'a', true];
        yield 'scalar rejects null' => ['scalar', null, false];
        yield 'string accepts string' => ['string', 'a', true];
        yield 'string rejects int' => ['string', 1, false];
    }

    #[DataProvider('atomicTypeProvider')]
    public function testAtomicTypes(string $type, mixed $value, bool $expected): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes([$type]);
        $this->assertSame($expected, $checker->hasAllowedType($value));
    }

    public function testResourceType(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['resource']);
        $resource = fopen('php://memory', 'r');
        $this->assertTrue($checker->hasAllowedType($resource));
        $this->assertFalse($checker->hasAllowedType('php://memory'));
        fclose($resource);
    }

    public function testClassNames(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes([ArrayObject::class]);
        $this->assertTrue($checker->hasAllowedType(new ArrayObject()));
        $this->assertFalse($checker->hasAllowedType(new stdClass()));
        $this->assertFalse($checker->hasAllowedType([]));
    }

    public function testClassNamesWithLeadingBackslash(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['\\' . ArrayObject::class]);
        $this->assertTrue($checker->hasAllowedType(new ArrayObject()));
    }

    public function testInterfaceNames(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes([Countable::class]);
        $this->assertTrue($checker->hasAllowedType(new ArrayObject()));
        // Unlike the `countable` keyword, the interface name must not match arrays.
        $this->assertFalse($checker->hasAllowedType([1, 2, 3]));
    }

    public function testClassNameCollidingWithBuiltInFunction(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes([File::class]);
        $this->assertTrue($checker->hasAllowedType(new File()));
        $this->assertFalse($checker->hasAllowedType(__FILE__));
    }

    public function testKeywordsAreCaseSensitive(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['Int']);
        $this->assertFalse($checker->hasAllowedType(1));
    }

    public function testMultipleTypes(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['int', 'string', ArrayObject::class]);
        $this->assertTrue($checker->hasAllowedType(1));
        $this->assertTrue($checker->hasAllowedType('a'));
        $this->assertTrue($checker->hasAllowedType(new ArrayObject()));
        $this->assertFalse($checker->hasAllowedType(1.5));
        $this->assertFalse($checker->hasAllowedType(null));
    }

    public function testIsAllowedType(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['int', ArrayObject::class]);
        $this->assertTrue($checker->isAllowedType('int'));
        $this->assertTrue($checker->isAllowedType(ArrayObject::class));
        $this->assertFalse($checker->isAllowedType('string'));
    }

    public function testSetAllowedTypesReindexesArray(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['a' => 'int', 'b' => 'string']);
        $this->assertSame(['int', 'string'], $checker->getAllowedTypes());
    }

    public function testSetAllowedTypesRejectsNonStrings(): void
    {
        $checker = new TypeCheckerFixture();
        $this->expectException(InvalidArgumentException::class);
        /** @phpstan-ignore argument.type */
        $checker->setAllowedTypes(['int', 1]);
    }

    public function testMixedAllowsEverything(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['mixed']);
        $this->assertTrue($checker->hasAllowedType(null));
        $this->assertTrue($checker->hasAllowedType(1));
        $this->assertTrue($checker->hasAllowedType(new stdClass()));
    }

    public function testUnionTypes(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['int|string', ' float | ' . ArrayObject::class . ' ']);
        $this->assertSame(['int', 'string', 'float', ArrayObject::class], $checker->getAllowedTypes());
        $this->assertTrue($checker->hasAllowedType(1));
        $this->assertTrue($checker->hasAllowedType('a'));
        $this->assertTrue($checker->hasAllowedType(1.5));
        $this->assertTrue($checker->hasAllowedType(new ArrayObject()));
        $this->assertFalse($checker->hasAllowedType(null));
    }

    public function testNullableTypes(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['?int', '?string']);
        $this->assertSame(['null', 'int', 'string'], $checker->getAllowedTypes());
        $this->assertTrue($checker->hasAllowedType(null));
        $this->assertTrue($checker->hasAllowedType(1));
        $this->assertFalse($checker->hasAllowedType(1.5));
    }

    public function testIsAllowedTypeWithUnionTypes(): void
    {
        $checker = new TypeCheckerFixture();
        $checker->setAllowedTypes(['int', 'null']);
        $this->assertTrue($checker->isAllowedType('?int'));
        $this->assertTrue($checker->isAllowedType('null|int'));
        $this->assertFalse($checker->isAllowedType('int|string'));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function emptyTypeProvider(): iterable
    {
        yield 'empty string' => [''];
        yield 'whitespace' => [' '];
        yield 'trailing pipe' => ['int|'];
        yield 'question mark only' => ['?'];
    }

    #[DataProvider('emptyTypeProvider')]
    public function testSetAllowedTypesRejectsEmptyTypes(string $type): void
    {
        $checker = new TypeCheckerFixture();
        $this->expectException(InvalidArgumentException::class);
        $checker->setAllowedTypes([$type]);
    }
}
