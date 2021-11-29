<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Test\Unit\Moodle\Domain;

use mod_matrix\Moodle;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \mod_matrix\Moodle\Domain\ModuleType
 */
final class ModuleTypeTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::arbitrary()
     */
    public function testFromIntReturnsType(int $value): void
    {
        $type = Moodle\Domain\ModuleType::fromInt($value);

        self::assertSame($value, $type->toInt());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::arbitrary()
     */
    public function testFromStringReturnsType(int $value): void
    {
        $type = Moodle\Domain\ModuleType::fromString((string) $value);

        self::assertSame($value, $type->toInt());
    }
}