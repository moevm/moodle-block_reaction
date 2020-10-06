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

class mse_ld_services external_api {
/*
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function set_reaction_parameters() {
        return new external_function_parameters(
                array('moduleid' => new external_value(PARAM_TEXT, 'Сourse id to which section will be added.'))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function set_reaction($moduleid) {
        
        global $USER;
        global $DB;

        /*First add section to the end.*/
        $reaction = new stdClass();
        $reaction->moduleid   = $courseid;
        $reaction->userid  = $USER->id;

        $reaction->id = $DB->insert_record("reactions", $reaction);

        return $reaction;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function set_reaction_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'reaction id'),
                'moduleid' => new external_value(PARAM_INT, 'module id'),
                'userid' => new external_value(PARAM_TEXT, 'user id'),
            )
        );
    }
}
