<?php

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || exit();

function xmldb_matrix_upgrade($oldversion = 0)
{
    global $DB;
    $dbman = $DB->get_manager();

    if (2020110901 > $oldversion) {
        $table = new xmldb_table('matrix');

        $field = new xmldb_field('name');
        $field->set_attributes(XMLDB_TYPE_CHAR, 255, false, true, false, 'Matrix Chat');
        $dbman->add_field($table, $field);

        $field = new xmldb_field('type');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 2, false, true, false, 0);
        $dbman->add_field($table, $field);

        upgrade_mod_savepoint(true, 2020110901, 'matrix');
    }

    if (2020110948 > $oldversion) {
        $table = new xmldb_table('matrix_rooms');

        $field = new xmldb_field('course');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 10, true, false, false, null);
        $dbman->rename_field($table, $field, 'course_id');

        $field = new xmldb_field('group');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 10, true, false, false, null);
        $dbman->rename_field($table, $field, 'group_id');

        upgrade_mod_savepoint(true, 2020110948, 'matrix');
    }

    if (2021091300 > $oldversion) {
        $table = new xmldb_table('matrix');

        $dbman->add_field(
            $table,
            new xmldb_field(
                'section',
                XMLDB_TYPE_INTEGER,
                10,
                true,
                true,
                false,
                0,
                'course'
            )
        );

        upgrade_mod_savepoint(
            true,
            2021091300,
            'matrix'
        );
    }

    return true;
}
