<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

\defined('MOODLE_INTERNAL') || exit();

require_once '../../config.php';

$id = required_param('id', PARAM_INT);           // Course ID

// Ensure that the course specified is valid
if (!$course = $DB->get_record('course', ['id' => $id])) {
    print_error('Course ID is incorrect');
}

echo 'Hello World'; // TODO: List instances
