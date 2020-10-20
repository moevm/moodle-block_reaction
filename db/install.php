<?php

require_once($CFG->libdir . '/datalib.php');

$courses = get_courses();

print_r($courses);
