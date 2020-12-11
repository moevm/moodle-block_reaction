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
 * Reaction block
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * block_reaction Class
 *
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/blocks/reaction/lib.php');
require_once($CFG->dirroot . '/blocks/reaction/externallib.php');

class block_reaction extends block_base {
    /**
     * Block initializations
     *
     * @throws coding_exception
     */
    public function init() {

        $this->title = get_string('pluginname', 'block_reaction');

    }

    /**
     * Database initialisation
     *
     * @throws dml_exception
     */
    public function instance_create() {
        global $COURSE, $DB;

        if (!is_null($this->page->cm)) {
            if (!$DB->record_exists('reactions_settings', ['moduleid' => $this->page->cm->id])) {
                init_module_block_settings($COURSE->id, $this->page->cm->id);
            }
        }
    }

    /**
     * Content of Reaction block
     *
     * @return Object
     * @throws dml_exception
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content() {
        global $USER, $COURSE, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';

        /* Build block */
        if ($this->page->user_is_editing()) {

            if (is_null($this->page->cm)) {

                $parameters = [
                    "courseid" => $COURSE->id
                ];

                $getpdfurl = new moodle_url("/blocks/reaction/export/pdf.php", $parameters);
                $getcsvurl = new moodle_url("/blocks/reaction/export/csv.php", $parameters);

                $this->content->text .=
                    html_writer::div(get_string('plugin_switcher_course', 'block_reaction'), "settings-header")
                    . html_writer::start_tag("div",
                        array(
                            "class" => "reactions-course-settings-wrapper reactions-settings",
                            "data-success-on" => get_string('success_on', 'block_reaction'),
                            "data-success-off" => get_string('success_off', 'block_reaction'),
                            "data-error" => get_string('error', 'block_reaction')
                        )
                    )

                        . html_writer::start_tag("div", array("class" => "all-on-btn-wrapper"))
                            . html_writer::span(get_string('all_on', 'block_reaction'), "plugin-btn-label")
                            . html_writer::tag("button", "", array("class" => "btn-on", "type" => "button",
                            "onclick" => "allTurnOn('" . $COURSE->id . "')"))
                        . html_writer::end_tag("div")

                        . html_writer::start_tag("div", array("class" => "all-off-btn-wrapper"))
                            . html_writer::span(get_string('all_off', 'block_reaction'), "plugin-btn-label")
                            . html_writer::tag("button", "", array("class" => "btn-off", "type" => "button",
                            "onclick" => "allTurnOff('" . $COURSE->id . "')"))
                        . html_writer::end_tag("div")

                    . html_writer::end_tag("div")

                    . html_writer::start_tag("div", array("class" => "reaction-link-wrapper"))
                        . get_string('download_statistics', 'block_reaction')

                            .html_writer::start_tag("div", array("class" => "reaction-links reactions-settings"))
                                . html_writer::start_tag("a",
                                    array(
                                        "class" => "get-statistics-button",
                                        "href" => $getpdfurl,
                                        "target" => "_blank",
                                        "rel" => "noopener noreferrer"
                                    ))
                                    . get_string('PDF', 'block_reaction')
                                . html_writer::end_tag("a")

                                . html_writer::start_tag("a",
                                    array(
                                        "class" => "get-statistics-button",
                                        "href" => $getcsvurl,
                                        "target" => "_blank",
                                        "rel" => "noopener noreferrer"
                                    ))
                                    . get_string('CSV', 'block_reaction')
                                . html_writer::end_tag("a")
                            .html_writer::end_tag("div")

                    . html_writer::end_tag("div");

            } else {

                $modulesettings = $DB->get_record('reactions_settings', ['moduleid' => $this->page->cm->id]);

                /* Settings for activity */
                $this->content->text .=
                    html_writer::div(get_string("plugin_switcher_module", 'block_reaction'), "settings-header")
                    . html_writer::start_tag("div", array("class" => "reactions-activity-settings-wrapper reactions-settings"))
                        . html_writer::span(get_string('on', 'block_reaction'), "plugin-state-label plugin-state-label-ON")

                        . html_writer::start_tag("label", array("class" => "checkbox"))
                            . html_writer::checkbox("plugin-state", "", ($modulesettings->visible == 1) ? false : true, "",
                              array("onclick" => "switcher('" . $this->page->cm->id . "')"))

                            . html_writer::div("", "checkbox__div")
                        . html_writer::end_tag("label")

                        . html_writer::span(get_string('off', 'block_reaction'), "plugin-state-label plugin-state-label-OFF")
                    . html_writer::end_tag("div");
            }
        }

        if (!is_null($this->page->cm)) {
            $modulesettings = $DB->get_record('reactions_settings', ['moduleid' => $this->page->cm->id]);
            if ($modulesettings->visible) {
                $userreaction = mse_ld_services::get_reaction($this->page->cm->id);
                $totalreaction = mse_ld_services::get_total_reaction($this->page->cm->id);

                $envconf = array(
                            'mod_id' => $this->page->cm->id,
                            'user_reaction' => $userreaction,
                            'total_reaction' => $totalreaction
                        );

                $paramsamd = array($envconf);
                $this->page->requires->js_call_amd('block_reaction/script_reaction', 'init', $paramsamd);
            }
        }

        $this->page->requires->css('/blocks/reaction/style.css');
        $this->page->requires->js('/blocks/reaction/script.js');

        return $this->content;
    }

    /**
     * Enable to add the block only in a course and modules
     *
     * @return array
     */
    public function applicable_formats() {
        return array(
            'course-view' => true,
            'mod' => true
        );
    }
}
