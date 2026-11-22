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

namespace OCC\Basics\Tests\Fixtures;

use OCC\Basics\Traits\Getter;
use OCC\Basics\Traits\Setter;
use RuntimeException;

use function strtoupper;

/**
 * A class using the Getter and Setter traits.
 *
 * @property string $name
 * @property-read ?string $readOnly
 * @property-read string $broken
 * @property-write string $writeOnly
 */
final class GetterSetterFixture
{
    use Getter;
    use Setter;

    protected string $name = 'initial';

    protected ?string $readOnly = 'read only';

    protected string $writeOnly = '';

    /** A property without magic methods. */
    protected string $hidden = 'hidden';

    /** A property whose getter throws an exception. */
    protected string $broken = 'broken';

    protected function _magicGetName(): string
    {
        return $this->name;
    }

    protected function _magicSetName(?string $value): void
    {
        $this->name = strtoupper((string) $value);
    }

    protected function _magicGetBroken(): string
    {
        throw new RuntimeException('Getter failed.');
    }

    protected function _magicGetReadOnly(): ?string
    {
        return $this->readOnly;
    }

    protected function _magicSetWriteOnly(?string $value): void
    {
        $this->writeOnly = (string) $value;
    }

    public function getWriteOnly(): string
    {
        return $this->writeOnly;
    }

    public function setReadOnlyToNull(): void
    {
        $this->readOnly = null;
    }
}
