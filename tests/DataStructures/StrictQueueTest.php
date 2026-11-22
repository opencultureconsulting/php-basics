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
use OCC\Basics\DataStructures\StrictQueue;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function iterator_to_array;
use function serialize;
use function unserialize;

final class StrictQueueTest extends TestCase
{
    public function testFirstInFirstOut(): void
    {
        $queue = new StrictQueue(['string']);
        $queue->enqueue('a');
        $queue->enqueue('b');
        $queue->enqueue('c');
        $this->assertSame(['a', 'b', 'c'], iterator_to_array($queue));
        $this->assertSame('a', $queue->dequeue());
        $this->assertSame('b', $queue->dequeue());
        $this->assertCount(1, $queue);
    }

    public function testEnqueueChecksType(): void
    {
        $queue = new StrictQueue(['string']);
        $this->expectException(InvalidDataTypeException::class);
        $queue->enqueue(1);
    }

    public function testDequeueOnEmptyQueueThrows(): void
    {
        $queue = new StrictQueue();
        $this->expectException(RuntimeException::class);
        $queue->dequeue();
    }

    public function testSerialization(): void
    {
        $queue = new StrictQueue(['string']);
        $queue->enqueue('a');
        $queue->enqueue('b');
        $copy = unserialize(serialize($queue));
        $this->assertInstanceOf(StrictQueue::class, $copy);
        $this->assertSame(['string'], $copy->getAllowedTypes());
        $this->assertSame('a', $copy->dequeue());
        $this->assertSame('b', $copy->dequeue());
    }
}
