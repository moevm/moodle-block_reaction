<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * On install function
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/datalib.php');
require_once($CFG->dirroot . '/blocks/reaction/lib.php');

/**
 * Add block to every course and module in system
 */
function xmldb_block_reaction_install() {
    global $PAGE, $DB;

    $courses = get_courses();
    foreach ($courses as $course) {
        add_block_to_course($course->id);

        $modules = course_modinfo::instance($course, 0)->get_cms();
        foreach ($modules as $module) {
            add_block_to_module($course->id, $module->id);
        }
    }
}
