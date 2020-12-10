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
 * Observers functions
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
/**
 * block_reaction_observer Class
 *
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_reaction_observer {

    /**
     * On new course created - add block
     * @param Object $event Event object
     */ 
    public static function course_created($event) {
        global $PAGE;
    
        $data = $event->get_data();
        $course = $event->get_record_snapshot($data['objecttable'], (int)$data['objectid']);
        
        $PAGE->reset_theme_and_output();
        $PAGE->set_course($course);
        $PAGE->set_pagetype('course-view');
        $PAGE->blocks->load_blocks();
        $PAGE->blocks->add_block_at_end_of_default_region('reaction');
    
        return true;    
    }
    
    /**
     * On new module created - add block
     * @param Object $event Event object
     */ 
    public static function course_module_created($event) {
        global $PAGE;
    
        $data = $event->get_data();
        $module = $event->get_record_snapshot($data['objecttable'], (int)$data['objectid']);
        
        $PAGE->set_cm($module);
        $PAGE->blocks->load_blocks();
        $PAGE->blocks->add_block_at_end_of_default_region('reaction');
    
        return true;
    }

}
