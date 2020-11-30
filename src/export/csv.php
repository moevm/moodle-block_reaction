<?php
require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/pdflib.php'); 
require_once($CFG->dirroot . '/course/lib.php'); 

$courseid = required_param('courseid', PARAM_INT);

$exportcourse = get_course($courseid);
$exportactivities = get_array_of_activities($courseid);

foreach($exportactivities as $activity) {
    $activity->likes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => true]);
    $activity->dislikes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => false]);
    $activity->total = $activity->likes + $activity->dislikes;
    $activity->likes_part = $activity->total ? round($activity->likes / $activity->total * 100, 0) : 0;
    $activity->dislikes_part = $activity->total ? round($activity->dislikes / $activity->total * 100, 0) : 0;
}

require_once($CFG->libdir . '/csvlib.class.php');

$writer = new csv_export_writer();

$writer->add_data(['Название активности', 'Количество лайков', 'Количество дизлайков']);
    
$total_likes = 0;
$total_likes_part = 0;
$total_dislikes = 0;
$total_dislikes_part = 0;
foreach($exportactivities as $activity) {
    $activity->likes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => true]);
    $activity->dislikes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => false]);
    
    $total_likes += $activity->likes;
    $total_likes_part += $activity->likes_part;
    
    $total_dislikes += $activity->dislikes;
    $total_dislikes_part += $activity->dislikes_part;
    
    $writer->add_data([$activity->name, $activity->likes, $activity->dislikes]);
    
}

$total = $total_likes + $total_dislikes; 

$writer->add_data(['Всего', $total_likes, $total_dislikes]);
$writer->add_data(['В среднем', count($exportactivities) ? round($total_likes / count($exportactivities), 2) : 0, 
count($exportactivities) ? round($total_dislikes / count($exportactivities), 2) : 0]);

$writer->add_data(['Всего, %', ($total ? round($total_likes / $total * 100, 0) : 0) . '%', ($total ? round($total_dislikes / $total * 100, 0) : 0) . '%']);

$writer->add_data(['В среднем, %', (count($exportactivities) ? round($total_likes_part / count($exportactivities), 0) : 0) . '%', 
(count($exportactivities) ? round($total_dislikes_part / count($exportactivities), 0) : 0) . '%']);

$writer->download_file();


