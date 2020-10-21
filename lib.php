<?php

function add_block_to_context($contextid, $pagetypepattern) {
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

    // If the new instance was created, allow it to do additional setup
    if ($block = block_instance('reaction', $blockinstance)) {
        $block->instance_create();
    }
}
