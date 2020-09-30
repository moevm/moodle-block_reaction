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

        $this->title = 'Hello World';

    }
    
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }
    
        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our SimpleHTML block!';
        $this->content->footer = 'Footer here...';
        
        $this->page->requires->js_call_amd('block_reaction/script_reaction', 'init');
        $this->page->requires->css('/blocks/reaction/style.css');
        $this->page->requires->js('/blocks/reaction/script.js');
    
        return $this->content;
    }
    
    public function applicable_formats() {
        return array(
            'mod' => true
        );
    }
}
