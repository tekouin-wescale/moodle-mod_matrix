<?php

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Moodle\Domain;

final class Module
{
    private $id;
    private $type;
    private $name;
    private $courseId;
    private $timecreated;
    private $timemodified;

    private function __construct(
        ModuleId $id,
        Type $type,
        Name $name,
        CourseId $courseId,
        Timestamp $timecreated,
        Timestamp $timemodified
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->courseId = $courseId;
        $this->timecreated = $timecreated;
        $this->timemodified = $timemodified;
    }

    public static function create(
        ModuleId $id,
        Type $type,
        Name $name,
        CourseId $courseId,
        Timestamp $timecreated,
        Timestamp $timemodified
    ): self {
        return new self(
            $id,
            $type,
            $name,
            $courseId,
            $timecreated,
            $timemodified
        );
    }

    public function id(): ModuleId
    {
        return $this->id;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function timecreated(): Timestamp
    {
        return $this->timecreated;
    }

    public function timemodified(): Timestamp
    {
        return $this->timemodified;
    }
}
