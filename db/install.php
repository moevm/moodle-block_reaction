<?php

require_once($CFG->libdir . '/datalib.php');
require_once($CFG->dirroot . '/blocks/reaction/lib.php');

function xmldb_block_reaction_install() {
    global $PAGE, $DB;

    $courses = get_courses();
    foreach ($courses as $course) {
        add_block_to_course($course->id);
        
        $modules = course_modinfo::instance($course, 0)->get_cms();        
        foreach ($modules as $module) {
            add_block_to_module($course->id, $module->id);
        }
    }
}
