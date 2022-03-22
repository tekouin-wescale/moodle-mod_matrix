<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Test\Unit\Plugin\Infrastructure;

use mod_matrix\Matrix;
use mod_matrix\Plugin;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \mod_matrix\Plugin\Infrastructure\MoodleFunctionBasedMatrixUserIdLoader
 */
final class MoodleFunctionBasedMatrixUserIdLoaderTest extends Framework\TestCase
{
    public function testConstants(): void
    {
        self::assertSame('matrix_user_id', Plugin\Infrastructure\MoodleFunctionBasedMatrixUserIdLoader::USER_PROFILE_FIELD_NAME);
    }
}