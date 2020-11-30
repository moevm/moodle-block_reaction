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

$pdfwriter = new pdf();
$pdfwriter->setPrintHeader(false);
$pdfwriter->AddPage();

$html = html_writer::tag('h3', 'Статистика курса "' . $exportcourse->fullname . '"');
$pdfwriter->writeHTML($html);

$html = html_writer::start_tag('table', ['cellspacing' => 0, 'cellpadding' => 2, 'border' => 1])
    . html_writer::start_tag('thead')
        . html_writer::start_tag('tr')
            . html_writer::tag('th', 'Название активности')
            . html_writer::tag('th', 'Количество лайков') 
            . html_writer::tag('th', 'Количество дизлайков') 
        . html_writer::end_tag('tr')
    . html_writer::end_tag('thead')
    . html_writer::end_tag('tbody');
    
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
    
    $html .= html_writer::start_tag('tr')
            . html_writer::tag('td', $activity->name)
            . html_writer::tag('td', $activity->likes) 
            . html_writer::tag('td', $activity->dislikes) 
          . html_writer::end_tag('tr');
}

$html .= html_writer::end_tag('tbody')
      . html_writer::end_tag('table');
      
$pdfwriter->writeHTML($html);

$total = $total_likes + $total_dislikes; 
 
$html = html_writer::start_tag('table', ['cellspacing' => 0, 'cellpadding' => 2, 'border' => 1])

        . html_writer::start_tag('tr')
            . html_writer::tag('td', 'Всего')
            . html_writer::tag('td', $total_likes) 
            . html_writer::tag('td', $total_dislikes) 
        . html_writer::end_tag('tr')
        
        . html_writer::start_tag('tr')
            . html_writer::tag('td', 'В среднем')
            . html_writer::tag('td', count($exportactivities) ? round($total_likes / count($exportactivities), 2) : 0) 
            . html_writer::tag('td', count($exportactivities) ? round($total_dislikes / count($exportactivities), 2) : 0) 
        . html_writer::end_tag('tr')

        . html_writer::start_tag('tr')
            . html_writer::tag('td', 'Всего, %')
            . html_writer::tag('td', ($total ? round($total_likes / $total * 100, 0) : 0) . '%') 
            . html_writer::tag('td', ($total ? round($total_dislikes / $total * 100, 0) : 0) . '%') 
        . html_writer::end_tag('tr')
        
        . html_writer::start_tag('tr')
            . html_writer::tag('td', 'В среднем, %')
            . html_writer::tag('td', (count($exportactivities) ? round($total_likes_part / count($exportactivities), 0) : 0) . '%') 
            . html_writer::tag('td', (count($exportactivities) ? round($total_dislikes_part / count($exportactivities), 0) : 0) . '%') 
        . html_writer::end_tag('tr')
        
    . html_writer::end_tag('table');
    
$pdfwriter->writeHTML($html);

$pdfwriter->Output('export' . $courseid . '.pdf');
