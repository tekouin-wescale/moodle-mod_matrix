<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Test\Unit\Matrix\Domain;

use mod_matrix\Matrix;
use mod_matrix\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \mod_matrix\Matrix\Domain\UserId
 */
final class UserIdTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \mod_matrix\Test\DataProvider\Matrix\Domain\UserIdProvider::invalid()
     */
    public function testFromStringRejectsInvalidUserId(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%s" does not appear to be a valid Matrix user identifier.',
            $value,
        ));

        Matrix\Domain\UserId::fromString($value);
    }

    /**
     * @dataProvider \mod_matrix\Test\DataProvider\Matrix\Domain\UserIdProvider::valid()
     */
    public function testFromStringReturnsUserId(string $value): void
    {
        $userId = Matrix\Domain\UserId::fromString($value);

        self::assertSame($value, $userId->toString());
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent(): void
    {
        $faker = self::faker();

        $one = Matrix\Domain\UserId::fromString(\sprintf(
            '@%s:%s',
            $faker->word(),
            $faker->domainName(),
        ));

        $two = Matrix\Domain\UserId::fromString(\sprintf(
            '@%s:%s',
            $faker->word(),
            $faker->domainName(),
        ));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $faker = self::faker();

        $value = \sprintf(
            '@%s:%s',
            $faker->word(),
            $faker->domainName(),
        );

        $one = Matrix\Domain\UserId::fromString($value);
        $two = Matrix\Domain\UserId::fromString($value);

        self::assertTrue($one->equals($two));
    }
}
