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
 * PDF export page
 *
 * @package    block_reaction
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/pdflib.php');
require_once($CFG->dirroot . '/course/lib.php');

$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);

$exportcourse = get_course($courseid);
$exportactivities = get_array_of_activities($courseid);

foreach ($exportactivities as $activity) {
    $activity->likes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => true]);
    $activity->dislikes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => false]);
    $activity->total = $activity->likes + $activity->dislikes;
    $activity->likes_part = $activity->total ? round($activity->likes / $activity->total * 100, 0) : 0;
    $activity->dislikes_part = $activity->total ? round($activity->dislikes / $activity->total * 100, 0) : 0;
}

$pdfwriter = new pdf();
$pdfwriter->setPrintHeader(false);
$pdfwriter->AddPage();

$html = html_writer::tag('h3', get_string('course_statistic', 'block_reaction') . ' "' . $exportcourse->fullname . '"');
$pdfwriter->writeHTML($html);

$html = html_writer::start_tag('table', ['cellspacing' => 0, 'cellpadding' => 2, 'border' => 1])
    . html_writer::start_tag('thead')
        . html_writer::start_tag('tr')
            . html_writer::tag('th', get_string('activity_name', 'block_reaction'))
            . html_writer::tag('th', get_string('likes_count', 'block_reaction'))
            . html_writer::tag('th', get_string('dislikes_count', 'block_reaction'))
        . html_writer::end_tag('tr')
    . html_writer::end_tag('thead')
    . html_writer::end_tag('tbody');

$totallikes = 0;
$totallikespart = 0;
$totaldislikes = 0;
$totaldislikespart = 0;
foreach ($exportactivities as $activity) {
    $activity->likes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => true]);
    $activity->dislikes = $DB->count_records('reactions', ['moduleid' => $activity->cm, 'reaction' => false]);

    $totallikes += $activity->likes;
    $totallikespart += $activity->likes_part;

    $totaldislikes += $activity->dislikes;
    $totaldislikespart += $activity->dislikes_part;

    $html .= html_writer::start_tag('tr')
            . html_writer::tag('td', $activity->name)
            . html_writer::tag('td', $activity->likes)
            . html_writer::tag('td', $activity->dislikes)
          . html_writer::end_tag('tr');
}

$html .= html_writer::end_tag('tbody')
      . html_writer::end_tag('table');

$pdfwriter->writeHTML($html);

$total = $totallikes + $totaldislikes;

$html = html_writer::start_tag('table', ['cellspacing' => 0, 'cellpadding' => 2, 'border' => 1])

        . html_writer::start_tag('tr')
            . html_writer::tag('td', get_string('total', 'block_reaction'))
            . html_writer::tag('td', $totallikes)
            . html_writer::tag('td', $totaldislikes)
        . html_writer::end_tag('tr')

        . html_writer::start_tag('tr')
            . html_writer::tag('td', get_string('average', 'block_reaction'))
            . html_writer::tag('td', count($exportactivities) ? round($totallikes / count($exportactivities), 2) : 0)
            . html_writer::tag('td', count($exportactivities) ? round($totaldislikes / count($exportactivities), 2) : 0)
        . html_writer::end_tag('tr')

        . html_writer::start_tag('tr')
            . html_writer::tag('td', get_string('total_percent', 'block_reaction'))
            . html_writer::tag('td', ($total ? round($totallikes / $total * 100, 0) : 0) . '%')
            . html_writer::tag('td', ($total ? round($totaldislikes / $total * 100, 0) : 0) . '%')
        . html_writer::end_tag('tr')

        . html_writer::start_tag('tr')
            . html_writer::tag('td', get_string('average_percent', 'block_reaction'))
            . html_writer::tag('td', (count($exportactivities) ? round($totallikespart / count($exportactivities), 0) : 0) . '%')
            . html_writer::tag('td', (count($exportactivities) ? round($totaldislikespart / count($exportactivities), 0) : 0) . '%')
        . html_writer::end_tag('tr')

    . html_writer::end_tag('table');

$pdfwriter->writeHTML($html);

$pdfwriter->Output('export' . $courseid . '.pdf');
