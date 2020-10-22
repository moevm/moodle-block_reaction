<?php

$observers = array(
 
    array(
        'eventname'   => '\core\event\course_created',
        'callback'    => 'block_reaction_observer::course_created',
    ),
    
    array(
        'eventname'   => '\core\event\course_module_created',
        'callback'    => 'block_reaction_observer::course_module_created',
    )
    
);
