<?php

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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External Web Service Template
 *
 * @package    localdigital
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 require_once($CFG->libdir . '/externallib.php');

class mse_ld_services extends external_api {

    public static function set_reaction_parameters() {
        return new external_function_parameters(
                array(
                'moduleid' => new external_value(PARAM_INT, 'Id of module to add reaction'),
                'reaction' => new external_value(PARAM_INT, 'reaction. 0 - dislike, 1 - like, 2 - undefined')
                ),
        );
    }

    public static function set_reaction($moduleid, $reaction) {
        
        global $USER;
        global $DB;
    
        if ($reaction < 2) {
            if ($DB->record_exists('reactions', ['userid' => $USER->id, 'moduleid' => $moduleid])) {
                $DB->set_field('reactions', 'reaction', $reaction, ['userid' => $USER->id, 'moduleid' => $moduleid]);
            } else {
                $reactionDatum = new stdClass();
                $reactionDatum->moduleid   = $moduleid;
                $reactionDatum->userid  = $USER->id;
                $reactionDatum->reaction  = $reaction;
                $reactionDatum->id = $DB->insert_record("reactions", $reactionDatum);
            }
        } else {
            $DB->delete_records('reactions', ['userid' => $USER->id, 'moduleid' => $moduleid]);
        }

        return true;
    }

    public static function set_reaction_returns() {
        return  new external_value(PARAM_BOOL, 'True if setting succesfull');
    }
    
    public static function get_reaction_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Id of module to get reaction'))
        );
    }

    public static function get_reaction($moduleid) {
        
        global $USER;
        global $DB;

        $reactionDatum = $DB->get_record("reactions", ['userid' => $USER->id, 'moduleid' => $moduleid]);
        
        if ($reactionDatum) {
            return $reactionDatum->reaction;
        } else {
            return 2;
        }
    }

    public static function get_reaction_returns() {
        return new external_value(PARAM_INT, 'reaction. 0 - dislike, 1 - like, 2 - undefined');
    }
    
    public static function get_total_reaction_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Module id which reaction will be returned'))
        );
    }

    public static function get_total_reaction($moduleid) {
        
        global $USER;
        global $DB;

        $reaction = new stdClass();
        $reaction->likes = $DB->count_records('reactions', ['moduleid' => $moduleid, 'reaction' => true]);
        $reaction->dislikes = $DB->count_records('reactions', ['moduleid' => $moduleid, 'reaction' => false]);

        return $reaction;
    }

    public static function get_total_reaction_returns() {
        return  new external_single_structure(
            array(
                'likes' => new external_value(PARAM_INT, 'Likes count'),
                'dislikes' => new external_value(PARAM_INT, 'Dislikes count')
            )
        );
    }
    
    public static function toggle_module_reaction_visibility_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_INT, 'Module id to disable reactions'))
        );
    }
    
    public static function toggle_module_reaction_visibility($moduleid) {
        
        global $DB;
        $moduleSettings = $DB->get_record('reactions_settings', ['moduleid' => $moduleid]);
        
        if ($moduleSettings) {
            $visible = ($moduleSettings->visible + 1) % 2;
            $DB->set_field('reactions_settings', 'visible', $visible, ['moduleid' => $moduleid]);
            return true;
        }
        return false;
        
    }
    
    public static function toggle_module_reaction_visibility_returns() {
        return  new external_value(PARAM_BOOL, 'true if succesfull');
    }
    
    public static function disable_course_modules_reactions_parameters() {
        return new external_function_parameters(
                array('courseid' => new external_value(PARAM_INT, 'Course id to disable reactions'))
        );
    }
    
    public static function disable_course_modules_reactions($courseid) {
        
        global $DB;
        
        $DB->set_field('reactions_settings', 'visible', 0, ['courseid' => $courseid]);
        return true;
        
    }
    
    public static function disable_course_modules_reactions_returns() {
        return  new external_value(PARAM_BOOL, 'true if succesfull');
    }
    
}
