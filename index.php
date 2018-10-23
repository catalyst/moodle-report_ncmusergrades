<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User Grade Report .
 *
 * @package    report_ncmusergrades
 * @author     Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @copyright  2018 Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
// require_once($CFG->libdir . '/gradelib.php');
// require_once($CFG->dirroot.'/grade/export/lib.php');
// require_once $CFG->dirroot . '/grade/report/overview/lib.php';
// require_once $CFG->dirroot . '/grade/lib.php';
// require_once $CFG->dirroot . '/grade/report/user/externallib.php';
// require_once $CFG->dirroot . '/grade/report/user/lib.php';
require_once $CFG->dirroot . '/report/ncmusergrades/lib.php';
require_once $CFG->dirroot . '/report/ncmusergrades/locallib.php';
// require_once $CFG->dirroot . '/user/lib.php';

require_login();

global $DB;

// $pagecontextid = required_param('pagecontextid', PARAM_INT);
// $context = context::instance_by_id($pagecontextid);
$context = context_system::instance();

require_capability('report/ncmusergrades:use', $context);

// \core_competency\api::require_enabled();

// if (!\core_competency\template::can_read_context($context)) {
//     throw new required_capability_exception($context, 'moodle/competency:templateview', 'nopermissions', '');
// }

// $urlparams = array('pagecontextid' => $pagecontextid);

// $url = new moodle_url('/report/ncmusergrades/index.php', $urlparams);
$url = new moodle_url('/report/ncmusergrades/index.php');

$title = get_string('pluginname', 'report_ncmusergrades');

if ($context->contextlevel == CONTEXT_SYSTEM) {
    $heading = $SITE->fullname;
} else if ($context->contextlevel == CONTEXT_COURSECAT) {
    $heading = $context->get_context_name();
} else {
    throw new coding_exception('Unexpected context!');
}

// Protect page based on capability
// require_capability('report/siteoutcomes:view', $context);
// Creating the form.
$mform = new \report_ncmusergrades\filter_form(null);

// Set css.
$PAGE->requires->css('/report/ncmusergrades/style/gradetable.css');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($heading);
$PAGE->set_pagelayout('admin'); // OR report

$PAGE->navbar->add(get_string('pluginname', 'report_ncmusergrades'));

$output = $PAGE->get_renderer('report_ncmusergrades');

echo $output->header();
echo $output->heading($title);

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form.
} else if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    // Set default data (if any)
    $mform->set_data($fromform);
    //displays the form
    $mform->display();

    // Get user details
    $user = $DB->get_record('user', array('username' => strtolower($fromform->userid)), '*', MUST_EXIST);
    $userdetails = user_get_user_details($user);
    echo ncmusergrades_user_desc($userdetails);
    // Get all the courses the user is enrolled into
    $courses = enrol_get_users_courses($user->id, false, 'id, shortname, showgrades');
    // echo "<pre>";
    // var_dump($courses);
    // echo "</pre>";

    // echo "<pre>";
    // var_dump($userdetails);
    // echo "</pre>";
    // echo "<h1><i class='fa fa-user-circle' aria-hidden='true'></i> {$userdetails['fullname']}</h1>";
    
    // Group the course by category
    $mycategories = array();
    foreach ($courses as $course) {
        // Get the category
        if (!isset($mycategories[$course->category])) {
            $mycategory = coursecat::get($course->category); // ->get_children();
            $mycategories[$mycategory->id]->category = $mycategory;
        }
        $mycategories[$mycategory->id]->courses[$course->id] = $course;
    }

    // Sort the categories, Most recent at the top
    usort($mycategories, function ($a, $b) {
        return strcmp($a->category->name, $b->category->name);
    });

    // echo "<pre>";
    // var_dump($mycategories);
    // echo "</pre>";


    foreach ($mycategories as $mycategory) {
        // echo "<pre>mycategory";
        // print_r($mycategory);
        // echo "</pre>";
        

        $mycourses = $mycategory->courses;

        foreach ($mycourses as $course) {

            echo "<h3>{$mycategory->category->name} / {$course->fullname} ({$course->shortname})</h3>";
            // echo "<pre>COURSE:";
            // var_dump($course);
            // echo "</pre>";    
    
            if (!$course->showgrades) {
                // continue;
            }
            $grade_items = grade_item::fetch_all(array('courseid' => $course->id));
    
            // echo "<pre>grade_items";
            // var_dump($grade_items);
            // echo "</pre>";    
    
            // Get the category
            // $mycategory = coursecat::get($course->category); // ->get_children();
    
            // echo "<pre>mycategory";
            // var_dump($mycategory);
            // echo "</pre>";
            /// return tracking object
            $gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'overview', 'userid'=>$user->id));
            $context = context_course::instance($course->id);
            $userreport = new ncm_grade_report_user($course->id, $gpr, $context, $user->id);
    
            // echo "<pre>userreport";
            // var_dump($userreport);
            // echo "</pre>";
            
            if ($userreport->fill_table()) {
                // echo '<br />'.$userreport->print_table(true);
                echo $userreport->print_table(true) . '<br/>';
            }
            // echo "<pre>USER";
            // var_dump($userreport->user);
            // echo "</pre>";
    
        }
    }
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
  //Set default data (if any)
  $mform->set_data($toform = array());
  //displays the form
  $mform->display();
}

// if ($mform->is_submitted()) {
//     echo "<pre>Hello World!</pre>";
//     $mform->display();
// }


// $page = new \report_ncmusergrades\output\report($context);
// echo $output->render($page);
echo $output->footer();