<?php

require_once($CFG->libdir . '/datalib.php');
require_once($CFG->dirroot . '/blocks/reaction/lib.php');

function xmldb_block_reaction_install() {
    global $PAGE, $DB;

    $courses = get_courses();
    foreach ($courses as $course) {
        
        $course_context = context_course::instance($course->id);
        add_block_to_context($course_context->id, 'course-view-*');
        
        $modules = course_modinfo::instance($course, 0)->get_cms();
        
        foreach ($modules as $module) {
            $module_context = context_module::instance($module->id);
            add_block_to_context($module_context->id, 'mod-*');
        }
    }
}
