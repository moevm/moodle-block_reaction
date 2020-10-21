<?php

function add_block($contextid, $pagetypepattern) {
    global $DB;

    $blockinstance = new stdClass;
    $blockinstance->blockname = 'reaction';
    $blockinstance->parentcontextid = $contextid;
    $blockinstance->showinsubcontexts = false;
    $blockinstance->pagetypepattern = $pagetypepattern;
    $blockinstance->subpagepattern = NULL;
    $blockinstance->defaultregion = 'en';
    $blockinstance->defaultweight = 0;
    $blockinstance->configdata = '';
    $blockinstance->timecreated = time();
    $blockinstance->timemodified = $blockinstance->timecreated;
    $blockinstance->id = $DB->insert_record('block_instances', $blockinstance);

    // Ensure the block context is created.
    context_block::instance($blockinstance->id);
}

function init_module_block_settings($courseid, $moduleid) {
    global $DB;
        
    $moduleSettings = new stdClass();
    $moduleSettings->moduleid = $moduleid;
    $moduleSettings->courseid = $courseid;
    $moduleSettings->visible = 1;
    
    $DB->insert_record("reactions_settings", $moduleSettings);
}

function add_block_to_course($courseid) {
    global $DB;
    
    $course_context = context_course::instance($courseid);
    add_block($course_context->id, 'course-view-*');
}

function add_block_to_module($courseid, $moduleid) {
    global $DB;
    
    $module_context = context_module::instance($moduleid);
    add_block($module_context->id, 'mod-*');
    init_module_block_settings($courseid, $moduleid);
}
