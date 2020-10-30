<?php

class block_reaction_observer {

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
