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

namespace OCC\Basics\Tests\ErrorHandlers;

use ErrorException;
use OCC\Basics\ErrorHandlers\ThrowErrorException;
use PHPUnit\Framework\TestCase;

use function error_reporting;
use function escapeshellarg;
use function exec;
use function implode;

use const PHP_BINARY;

final class ErrorHandlersTest extends TestCase
{
    public function testThrowErrorExceptionThrowsForReportedErrors(): void
    {
        $handler = new ThrowErrorException();
        // PHPUnit lowers the error reporting level while its own error handler is active.
        $previous = error_reporting(\E_ALL);
        try {
            $handler(\E_USER_WARNING, 'Test warning', __FILE__, 42);
            $this->fail('No exception thrown.');
        } catch (ErrorException $exception) {
            $this->assertSame('Test warning', $exception->getMessage());
            $this->assertSame(\E_USER_WARNING, $exception->getSeverity());
            $this->assertSame(__FILE__, $exception->getFile());
            $this->assertSame(42, $exception->getLine());
        } finally {
            error_reporting($previous);
        }
    }

    public function testThrowErrorExceptionIgnoresUnreportedErrors(): void
    {
        $handler = new ThrowErrorException();
        $previous = error_reporting(\E_ALL & ~\E_USER_NOTICE);
        try {
            $this->assertFalse($handler(\E_USER_NOTICE, 'Ignored notice'));
        } finally {
            error_reporting($previous);
        }
    }

    public function testTriggerExceptionErrorTriggersWarningAndExits(): void
    {
        $command = implode(' ', [
            escapeshellarg(PHP_BINARY),
            '-n',
            '-d display_errors=stdout',
            '-d error_reporting=-1',
            escapeshellarg(__DIR__ . '/../Fixtures/trigger-exception-error.php'),
            '2>&1'
        ]);
        exec($command, $output, $exitCode);
        $this->assertSame(255, $exitCode);
        $output = implode("\n", $output);
        $this->assertStringContainsString('Warning', $output);
        $this->assertStringContainsString('Uncaught Exception [42]', $output);
        $this->assertStringContainsString('trigger-exception-error.php', $output);
        $this->assertStringContainsString('Something went wrong', $output);
    }
}
