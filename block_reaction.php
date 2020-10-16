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

            if (is_null($this->page->cm)) {

                $this->content->text .=
                    html_writer::div("Plugin switcher for all activities", "settings-header")
                    . html_writer::start_tag("div", array("class" => "reactions-course-settings-wrapper reactions-settings"))
                        
                        . html_writer::start_tag("div", array("class" => "all-on-btn-wrapper"))
                            . html_writer::span("All on", "plugin-btn-label")
                            . html_writer::tag("button", "", array("class" => "btn-on", "type" => "button"))
                        . html_writer::end_tag("div")

                        . html_writer::start_tag("div", array("class" => "all-off-btn-wrapper"))
                            . html_writer::span("All off", "plugin-btn-label")
                            . html_writer::tag("button", "", array("class" => "btn-off", "type" => "button"))
                        . html_writer::end_tag("div")

                    . html_writer::end_tag("div");

            } else {

                // settings for activity
                $this->content->text .=
                    html_writer::div("Plugin switcher", "settings-header")
                    . html_writer::start_tag("div", array("class" => "reactions-activity-settings-wrapper reactions-settings"))
                        . html_writer::span("ON", "plugin-state-label plugin-state-label-ON")

                        . html_writer::start_tag("label", array("class" => "checkbox"))
                            . html_writer::checkbox("plugin-state", "")
                            . html_writer::div("", "checkbox__div")
                        . html_writer::end_tag("label")

                        . html_writer::span("OFF", "plugin-state-label plugin-state-label-OFF")
                    . html_writer::end_tag("div");
            }

            /* Debug parameters */
//            $this->content->text .= 'User: ' . $USER->id . '<br>';
//            $this->content->text .= 'Course: ' . $COURSE->id . '<br>';
//            $this->content->text .= 'Module: ' . $this->page->cm->id . '<br>';
        }

        $user_reaction = mse_ld_services::get_reaction($this->page->cm->id);
        $total_reaction = mse_ld_services::get_total_reaction($this->page->cm->id);
        
        $envconf = array(
                    'mod_id' => $this->page->cm->id,
                    'user_reaction' => $user_reaction,
                    'total_reaction' => $total_reaction
                );

        $paramsamd = array($envconf);
        
        if (!is_null($this->page->cm)) {
        
            $moduleSettings = $DB->get_record('reactions_settings', ['moduleid' => $this->page->cm->id]);
            if ($moduleSettings) {
                if ($this->page->user_is_editing()) {
//                     $this->content->text .= 'Visible: ' . $moduleSettings->visible . '<br>';
                }
            } else {
                $moduleSettings = new stdClass();
                $moduleSettings->moduleid = $this->page->cm->id;
                $moduleSettings->courseid = $COURSE->id;
                $moduleSettings->visible = 1;
                $DB->insert_record("reactions_settings", $moduleSettings);
            }
        
            if ($moduleSettings->visible) {
                $this->page->requires->js_call_amd('block_reaction/script_reaction', 'init', $paramsamd);
            }
        }

        $this->page->requires->css('/blocks/reaction/style.css');
        $this->page->requires->js('/blocks/reaction/script.js');
    
        return $this->content;
    }
    
    public function applicable_formats() {
        return array(
            'course-view' => true,
            'mod' => true
        );
    }
}
