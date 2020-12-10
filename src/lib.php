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
 * Web-service functions
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Add block to database. Used in case, when we can't add block via page constant
 * @param int $contextid Context ID
 * @param string $pagetypepattern Pattern of page type
 * @throws dml_exception
 */
function add_block($contextid, $pagetypepattern) {
    global $DB;

    $blockinstance = new stdClass;
    $blockinstance->blockname = 'reaction';
    $blockinstance->parentcontextid = $contextid;
    $blockinstance->showinsubcontexts = false;
    $blockinstance->pagetypepattern = $pagetypepattern;
    $blockinstance->subpagepattern = null;
    $blockinstance->defaultregion = 'en';
    $blockinstance->defaultweight = 0;
    $blockinstance->configdata = '';
    $blockinstance->timecreated = time();
    $blockinstance->timemodified = $blockinstance->timecreated;
    $blockinstance->id = $DB->insert_record('block_instances', $blockinstance);

    // Ensure the block context is created.
    context_block::instance($blockinstance->id);
}

/**
 * Init settings for block in module
 * @param int $courseid Course ID
 * @param int $moduleid Module ID
 * @throws dml_exception
 */
function init_module_block_settings($courseid, $moduleid) {
    global $DB;

    $modulesettings = new stdClass();
    $modulesettings->moduleid = $moduleid;
    $modulesettings->courseid = $courseid;
    $modulesettings->visible = 1;

    $DB->insert_record("reactions_settings", $modulesettings);
}

/**
 * Add block to course page
 * @param int $courseid Course ID
 * @throws dml_exception
 */
function add_block_to_course($courseid) {
    global $DB;

    $coursecontext = context_course::instance($courseid);
    add_block($coursecontext->id, 'course-view-*');
}

/**
 * Add block to module page
 * @param int $courseid Course ID
 * @param int $moduleid Module ID
 * @throws dml_exception
 */
function add_block_to_module($courseid, $moduleid) {
    global $DB;

    $modulecontext = context_module::instance($moduleid);
    add_block($modulecontext->id, 'mod-*');
    init_module_block_settings($courseid, $moduleid);
}
