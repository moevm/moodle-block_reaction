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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localdigital
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
        'mse_ld_set_reaction' => array(
                'classname'   => 'mse_ld_services',
                'methodname'  => 'set_reaction',
                'classpath'   => 'blocks/reaction/externallib.php',
                'description' => 'Set user reaction for activity.',
                'type'        => 'write',
                'ajax'        => 'true',
                'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ),
        'mse_ld_get_reaction' => array(
                'classname'   => 'mse_ld_services',
                'methodname'  => 'get_reaction',
                'classpath'   => 'blocks/reaction/externallib.php',
                'description' => 'Return user reaction for activity',
                'type'        => 'write',
                'ajax'        => 'true',
                'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ),
        'mse_ld_get_total_reaction' => array(
                'classname' => 'mse_ld_services',
                'methodname'  => 'get_total_reaction',
                'classpath'   => 'blocks/reaction/externallib.php',
                'description' => 'Return all users reaction for activity.',
                'type'        => 'write',
                'ajax'        => 'true',
                'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ),
        'mse_ld_toggle_module_reaction_visibility' => array(
                'classname' => 'mse_ld_services',
                'methodname'  => 'toggle_module_reaction_visibility',
                'classpath'   => 'blocks/reaction/externallib.php',
                'description' => 'Return all users reaction for activity.',
                'type'        => 'write',
                'ajax'        => 'true',
                'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ),
        'mse_ld_disable_course_modules_reactions' => array(
                'classname' => 'mse_ld_services',
                'methodname'  => 'disable_course_modules_reactions',
                'classpath'   => 'blocks/reaction/externallib.php',
                'description' => 'Return all users reaction for activity.',
                'type'        => 'write',
                'ajax'        => 'true',
                'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        )
);
