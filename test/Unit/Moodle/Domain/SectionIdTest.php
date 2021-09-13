<?php

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
 * @covers \mod_matrix\Moodle\Domain\SectionId
 */
final class SectionIdTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::arbitrary()
     */
    public function testFromIntReturnsSectionId(int $value): void
    {
        $sectionId = Moodle\Domain\SectionId::fromInt($value);

        self::assertSame($value, $sectionId->toInt());
    }

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::arbitrary()
     */
    public function testFromStringReturnsSectionId(int $value): void
    {
        $sectionId = Moodle\Domain\SectionId::fromString((string) $value);

        self::assertSame($value, $sectionId->toInt());
    }
}
