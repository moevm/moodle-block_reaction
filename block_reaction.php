<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/blocks/reaction/externallib.php');

/**
 * Reaction block
 *
 *
 * @package    block_reaction
 */

/**
 * block_reaction Class
 *
 *
 * @package    block_reaction
 */
class block_reaction extends block_base {
    /**
     * Block initializations
     */
    public function init() {

        $this->title = get_string('pluginname', 'block_reaction');

    }
    
    public function get_content() {
        global $USER, $COURSE, $DB;
    
        if ($this->content !== null) {
            return $this->content;
        }
    
        $this->content = new stdClass;
        if ($this->page->user_is_editing()) {
            $this->content->text .= 'Course: ' . $COURSE->id . '<br>';
            $this->content->text .= 'User: ' . $USER->id;
        }

        //check if the service exists and is enabled
        $service = $DB->get_record('external_services', array('shortname' => MOODLE_OFFICIAL_MOBILE_SERVICE, 'enabled' => 1));
        if (empty($service)) {
            // will throw exception if no token found
            throw new moodle_exception('servicenotavailable', 'webservice');
        }

        // Get an existing token or create a new one.
        $token = external_generate_token_for_current_user($service);
        $user_reaction = mse_ld_services::get_reaction($this->page->cm->id);
        $total_reaction = mse_ld_services::get_total_reaction($this->page->cm->id);
        
        $envconf = array(
                    'mod_id' => $this->page->cm->id,
                    'token' => $token->token,
                    'user_reaction' => $user_reaction,
                    'total_reaction' => $total_reaction
                );

        $paramsamd = array($envconf);
        
        if (!is_null($this->page->cm)) {
            $this->page->requires->js_call_amd('block_reaction/script_reaction', 'init', $paramsamd);
            $this->page->requires->css('/blocks/reaction/style.css');
            $this->page->requires->js('/blocks/reaction/script.js');
        }
    
        return $this->content;
    }
    
    public function applicable_formats() {
        return array(
            'course-view' => true,
            'mod' => true
        );
    }
}
