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
            $this->content->text = 'The content of our SimpleHTML block!';
        }
        
        $dbdatum = new stdClass();
        $dbdatum->userid = 5;
        $dbdatum->moduleid = 7;
        $dbdatum->reaction = 1;
        
        $DB->insert_record('reactions', $dbdatum);
        
        $envconf = array(
                    'user' => $USER,
                    'course' => $COURSE,
                    'mod_id' => $this->page->cm->id,
                    'db_datum' => $DB->get_record('reactions', ['userid' => 5, 'moduleid' => 7])
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
