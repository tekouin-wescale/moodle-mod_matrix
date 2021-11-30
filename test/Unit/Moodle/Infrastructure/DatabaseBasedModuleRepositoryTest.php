<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Test\Unit\Moodle\Infrastructure;

use mod_matrix\Moodle;
use mod_matrix\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \mod_matrix\Moodle\Infrastructure\DatabaseBasedModuleRepository
 *
 * @uses \mod_matrix\Moodle\Domain\CourseId
 * @uses \mod_matrix\Moodle\Domain\Module
 * @uses \mod_matrix\Moodle\Domain\ModuleId
 * @uses \mod_matrix\Moodle\Domain\ModuleName
 * @uses \mod_matrix\Moodle\Domain\ModuleTopic
 * @uses \mod_matrix\Moodle\Domain\ModuleType
 * @uses \mod_matrix\Moodle\Domain\SectionId
 * @uses \mod_matrix\Moodle\Domain\Timestamp
 * @uses \mod_matrix\Moodle\Infrastructure\ModuleNormalizer
 */
final class DatabaseBasedModuleRepositoryTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testConstants(): void
    {
        self::assertSame('matrix', Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE);
    }

    public function testFindOneByReturnsNullWhenModuleCouldNotBeFound(): void
    {
        $faker = self::faker()->unique();

        $conditions = [
            'id' => $faker->numberBetween(1),
        ];

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('get_record')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::identicalTo($conditions),
                self::identicalTo('*'),
                self::identicalTo(IGNORE_MISSING),
            )
            ->willReturn(null);

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        self::assertNull($moduleRepository->findOneBy($conditions));
    }

    public function testFindOneByReturnsModuleWhenModuleCouldBeFound(): void
    {
        $faker = self::faker();

        $id = $faker->numberBetween(1);

        $conditions = [
            'id' => $id,
        ];

        $normalized = (object) [
            'course' => $faker->numberBetween(1),
            'id' => $id,
            'name' => $faker->sentence(),
            'section' => $faker->numberBetween(1),
            'timecreated' => $faker->dateTime()->getTimestamp(),
            'timemodified' => $faker->dateTime()->getTimestamp(),
            'topic' => $faker->sentence(),
            'type' => $faker->numberBetween(1),
        ];

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('get_record')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::identicalTo($conditions),
                self::identicalTo('*'),
                self::identicalTo(IGNORE_MISSING),
            )
            ->willReturn($normalized);

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $expected = Moodle\Domain\Module::create(
            Moodle\Domain\ModuleId::fromString((string) $normalized->id),
            Moodle\Domain\ModuleType::fromString((string) $normalized->type),
            Moodle\Domain\ModuleName::fromString((string) $normalized->name),
            Moodle\Domain\ModuleTopic::fromString((string) $normalized->topic),
            Moodle\Domain\CourseId::fromString((string) $normalized->course),
            Moodle\Domain\SectionId::fromString((string) $normalized->section),
            Moodle\Domain\Timestamp::fromString((string) $normalized->timecreated),
            Moodle\Domain\Timestamp::fromString((string) $normalized->timemodified),
        );

        self::assertEquals($expected, $moduleRepository->findOneBy($conditions));
    }

    public function testFindAllByReturnsModulesForConditions(): void
    {
        $faker = self::faker();

        $courseId = $faker->numberBetween(1);

        $conditions = [
            'course' => $courseId,
        ];

        $one = (object) [
            'course' => $courseId,
            'id' => $faker->numberBetween(1),
            'name' => $faker->sentence(),
            'section' => $faker->numberBetween(1),
            'timecreated' => $faker->dateTime()->getTimestamp(),
            'timemodified' => $faker->dateTime()->getTimestamp(),
            'topic' => $faker->sentence(),
            'type' => $faker->numberBetween(1),
        ];

        $two = (object) [
            'course' => $courseId,
            'id' => $faker->numberBetween(1),
            'name' => $faker->sentence(),
            'section' => $faker->numberBetween(1),
            'timecreated' => $faker->dateTime()->getTimestamp(),
            'timemodified' => $faker->dateTime()->getTimestamp(),
            'topic' => $faker->sentence(),
            'type' => $faker->numberBetween(1),
        ];

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('get_records')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::identicalTo($conditions),
            )
            ->willReturn([
                $one,
                $two,
            ]);

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $expected = [
            Moodle\Domain\Module::create(
                Moodle\Domain\ModuleId::fromString((string) $one->id),
                Moodle\Domain\ModuleType::fromString((string) $one->type),
                Moodle\Domain\ModuleName::fromString((string) $one->name),
                Moodle\Domain\ModuleTopic::fromString((string) $one->topic),
                Moodle\Domain\CourseId::fromString((string) $one->course),
                Moodle\Domain\SectionId::fromString((string) $one->section),
                Moodle\Domain\Timestamp::fromString((string) $one->timecreated),
                Moodle\Domain\Timestamp::fromString((string) $one->timemodified),
            ),
            Moodle\Domain\Module::create(
                Moodle\Domain\ModuleId::fromString((string) $two->id),
                Moodle\Domain\ModuleType::fromString((string) $two->type),
                Moodle\Domain\ModuleName::fromString((string) $two->name),
                Moodle\Domain\ModuleTopic::fromString((string) $two->topic),
                Moodle\Domain\CourseId::fromString((string) $two->course),
                Moodle\Domain\SectionId::fromString((string) $two->section),
                Moodle\Domain\Timestamp::fromString((string) $two->timecreated),
                Moodle\Domain\Timestamp::fromString((string) $two->timemodified),
            ),
        ];

        self::assertEquals($expected, $moduleRepository->findAllBy($conditions));
    }

    public function testSaveInsertsRecordForModuleWhenModuleHasNotBeenPersistedYet(): void
    {
        $faker = self::faker();

        $id = $faker->numberBetween(1);

        $module = Moodle\Domain\Module::create(
            Moodle\Domain\ModuleId::unknown(),
            Moodle\Domain\ModuleType::fromInt($faker->numberBetween(1)),
            Moodle\Domain\ModuleName::fromString($faker->sentence()),
            Moodle\Domain\ModuleTopic::fromString($faker->sentence()),
            Moodle\Domain\CourseId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\SectionId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
        );

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('insert_record')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::equalTo((object) [
                    'course' => $module->courseId()->toInt(),
                    'id' => $module->id()->toInt(),
                    'name' => $module->name()->toString(),
                    'section' => $module->sectionId()->toInt(),
                    'timecreated' => $module->timecreated()->toInt(),
                    'timemodified' => $module->timemodified()->toInt(),
                    'topic' => $module->topic()->toString(),
                    'type' => $module->type()->toInt(),
                ]),
            )
            ->willReturn($id);

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $moduleRepository->save($module);

        self::assertSame($id, $module->id()->toInt());
    }

    public function testSaveUpdatesRecordForModuleWhenModuleHasBeenPersisted(): void
    {
        $faker = self::faker();

        $id = Moodle\Domain\ModuleId::fromInt($faker->numberBetween(1));

        $module = Moodle\Domain\Module::create(
            $id,
            Moodle\Domain\ModuleType::fromInt($faker->numberBetween(1)),
            Moodle\Domain\ModuleName::fromString($faker->sentence()),
            Moodle\Domain\ModuleTopic::fromString($faker->sentence()),
            Moodle\Domain\CourseId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\SectionId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
        );

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('update_record')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::equalTo((object) [
                    'course' => $module->courseId()->toInt(),
                    'id' => $module->id()->toInt(),
                    'name' => $module->name()->toString(),
                    'section' => $module->sectionId()->toInt(),
                    'timecreated' => $module->timecreated()->toInt(),
                    'timemodified' => $module->timemodified()->toInt(),
                    'topic' => $module->topic()->toString(),
                    'type' => $module->type()->toInt(),
                ]),
            );

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $moduleRepository->save($module);

        self::assertSame($id, $module->id());
    }

    public function testRemoveRejectsModuleWhenModuleHasNotYetBeenPersisted(): void
    {
        $faker = self::faker();

        $module = Moodle\Domain\Module::create(
            Moodle\Domain\ModuleId::unknown(),
            Moodle\Domain\ModuleType::fromInt($faker->numberBetween(1)),
            Moodle\Domain\ModuleName::fromString($faker->sentence()),
            Moodle\Domain\ModuleTopic::fromString($faker->sentence()),
            Moodle\Domain\CourseId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\SectionId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
        );

        $database = $this->createMock(\moodle_database::class);

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not remove module that has not yet been persisted.');

        $moduleRepository->remove($module);
    }

    public function testRemoveDeletesRecordForModuleWhenModuleHasBeenPersisted(): void
    {
        $faker = self::faker();

        $module = Moodle\Domain\Module::create(
            Moodle\Domain\ModuleId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\ModuleType::fromInt($faker->numberBetween(1)),
            Moodle\Domain\ModuleName::fromString($faker->sentence()),
            Moodle\Domain\ModuleTopic::fromString($faker->sentence()),
            Moodle\Domain\CourseId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\SectionId::fromInt($faker->numberBetween(1)),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
            Moodle\Domain\Timestamp::fromInt($faker->dateTime()->getTimestamp()),
        );

        $database = $this->createMock(\moodle_database::class);

        $database
            ->expects(self::once())
            ->method('delete_records')
            ->with(
                self::identicalTo(Moodle\Infrastructure\DatabaseBasedModuleRepository::TABLE),
                self::identicalTo([
                    'id' => $module->id()->toInt(),
                ]),
            );

        $moduleRepository = new Moodle\Infrastructure\DatabaseBasedModuleRepository(
            $database,
            new Moodle\Infrastructure\ModuleNormalizer(),
        );

        $moduleRepository->remove($module);
    }
}
