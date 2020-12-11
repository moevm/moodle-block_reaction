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
 * mse_ld_services Class
 *
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/moodlelib.php');

class mse_ld_services extends external_api {


    /**
     * Returns description of set_reaction_parameters method parameters
     * @return external_function_parameters
     */
    public static function set_reaction_parameters() {
        return new external_function_parameters(
                array(
                'moduleid' => new external_value(PARAM_INT, 'Id of module to add reaction'),
                'reaction' => new external_value(PARAM_INT, 'reaction. 0 - dislike, 1 - like, 2 - undefined')
                )
        );
    }

    /**
     * Set user reaction
     * @param int $moduleid Module ID
     * @param int $reaction 0 - dislike, 1 - like, else - no reaction
     * @throws dml_exception
     * @return bool
     */
    public static function set_reaction($moduleid, $reaction) {

        global $DB, $USER;

        $module = $DB->get_record('course_modules', array('id' => $moduleid));
        $courseid = $module->course;
        require_login($courseid, true, $module);

        if ($reaction < 2) {
            if ($DB->record_exists('reactions', ['userid' => $USER->id, 'moduleid' => $moduleid])) {
                $DB->set_field('reactions', 'reaction', $reaction, ['userid' => $USER->id, 'moduleid' => $moduleid]);
            } else {
                $reactiondatum = new stdClass();
                $reactiondatum->moduleid   = $moduleid;
                $reactiondatum->userid  = $USER->id;
                $reactiondatum->reaction  = $reaction;
                $reactiondatum->id = $DB->insert_record("reactions", $reactiondatum);
            }
        } else {
            $DB->delete_records('reactions', ['userid' => $USER->id, 'moduleid' => $moduleid]);
        }

        return true;
    }

    /**
     * Returns description of set_reaction_returns method result value
     * @return external_description
     */
    public static function set_reaction_returns() {
        return  new external_value(PARAM_BOOL, 'True if setting succesfull');
    }

    /**
     * Returns description of get_reaction_parameters method parameters
     * @return external_function_parameters
     */
    public static function get_reaction_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Id of module to get reaction'))
        );
    }

    /**
     * Get user reaction
     * @param int $moduleid Module ID
     * @throws dml_exception
     * @return int 0-dislike, 1-like, else - noreaction
     */
    public static function get_reaction($moduleid) {

        global $DB, $PAGE, $USER;

        $module = $DB->get_record('course_modules', array('id' => $moduleid));
        $courseid = $module->course;
        require_login($courseid, true, $module);

        $reactiondatum = $DB->get_record("reactions", ['userid' => $USER->id, 'moduleid' => $moduleid]);

        if ($reactiondatum) {
            return $reactiondatum->reaction;
        } else {
            return 2;
        }
    }

    /**
     * Returns description of get_reaction_parameters method result value
     * @return external_description
     */
    public static function get_reaction_returns() {
        return new external_value(PARAM_INT, 'reaction. 0 - dislike, 1 - like, 2 - undefined');
    }

    /**
     * Returns description of get_total_reaction_parameters method parameters
     * @return external_function_parameters
     */
    public static function get_total_reaction_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Module id which reaction will be returned'))
        );
    }

    /**
     * Get reaction for module
     * @param int $moduleid Module ID
     * @throws dml_exception
     * @return Object
     */
    public static function get_total_reaction($moduleid) {

        global $USER;
        global $DB;

        $module = $DB->get_record('course_modules', array('id' => $moduleid));
        $courseid = $module->course;
        require_login($courseid, true, $module);

        $reaction = new stdClass();
        $reaction->likes = $DB->count_records('reactions', ['moduleid' => $moduleid, 'reaction' => true]);
        $reaction->dislikes = $DB->count_records('reactions', ['moduleid' => $moduleid, 'reaction' => false]);

        return $reaction;
    }

    /**
     * Returns description of get_total_reaction_parameters method result value
     * @return external_description
     */
    public static function get_total_reaction_returns() {
        return  new external_single_structure(
            array(
                'likes' => new external_value(PARAM_INT, 'Likes count'),
                'dislikes' => new external_value(PARAM_INT, 'Dislikes count')
            )
        );
    }

    /**
     * Returns description of toggle_module_reaction_visibility method parameters
     * @return external_function_parameters
     */
    public static function toggle_module_reaction_visibility_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Module id to disable reactions'))
        );
    }

    /**
     * Set visibility setting
     * @param int $moduleid Module ID
     * @throws dml_exception
     * @return bool
     */
    public static function toggle_module_reaction_visibility($moduleid) {

        global $DB, $PAGE;

        $module = $DB->get_record('course_modules', array('id' => $moduleid));
        $courseid = $module->course;
        require_login($courseid, true, $module);

        if (!$PAGE->user_allowed_editing()) {
            return false;
        }

        $modulesettings = $DB->get_record('reactions_settings', ['moduleid' => $moduleid]);

        if ($modulesettings) {
            $visible = ($modulesettings->visible + 1) % 2;
            $DB->set_field('reactions_settings', 'visible', $visible, ['moduleid' => $moduleid]);
            return true;
        }
        return false;
    }

    /**
     * Returns description of toggle_module_reaction_visibility method result value
     * @return external_description
     */
    public static function toggle_module_reaction_visibility_returns() {
        return  new external_value(PARAM_BOOL, 'true if succesfull');
    }

    /**
     * Returns description of get_module_reactions_visibility method parameters
     * @return external_function_parameters
     */
    public static function get_module_reactions_visibility_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Get module reactions visibility'))
        );
    }

    /**
     * Get visibility setting for moduke
     * @param int $moduleid Module ID
     * @throws dml_exception
     * @return bool
     */
    public static function get_module_reactions_visibility($moduleid) {
        global $DB;

        $module = $DB->get_record('course_modules', array('id' => $moduleid));
        $courseid = $module->course;
        require_login($courseid, true, $module);

        $modulesettings = $DB->get_record('reactions_settings', ['moduleid' => $moduleid]);

        return $modulesettings->visible;
    }

    /**
     * Returns description of get_module_reactions_visibility method result value
     * @return external_description
     */
    public static function get_module_reactions_visibility_returns() {
        return  new external_value(PARAM_BOOL, 'false - invisible, true - visible');
    }

    /**
     * Returns description of set_course_modules_reactions_visible method parameters
     * @return external_function_parameters
     */
    public static function set_course_modules_reactions_visible_parameters() {
        return new external_function_parameters(
                array(
                'courseid' => new external_value(PARAM_INT, 'Course id to disable reactions'),
                'visible' => new external_value(PARAM_INT, 'Module visibility, 0 - false, 1 - true')
                )
        );
    }

    /**
     * Set visibility setting for every module in course
     * @param int $courseid Course ID
     * @param int $visible 0 - invisible, 1 - visible
     * @throws dml_exception
     * @return bool
     */
    public static function set_course_modules_reactions_visible($courseid, $visible) {

        global $DB, $PAGE;

        require_login($courseid);
        if (!$PAGE->user_allowed_editing()) {
            return false;
        }

        $DB->set_field('reactions_settings', 'visible', $visible, ['courseid' => $courseid]);
        return true;

    }

    /**
     * Returns description of set_course_modules_reactions_visible method result value
     * @return external_description
     */
    public static function set_course_modules_reactions_visible_returns() {
        return  new external_value(PARAM_BOOL, 'true if succesfull');
    }
}
