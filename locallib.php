<?php
// This file is part of the Zoom plugin for Moodle - http://moodle.org/
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
 * Internal library of functions for module zoom
 *
 * All the zoom specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    report_ncmusergrades
 * @copyright  2015 UC Regents
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

function ncmusergrades_user_desc($userdetails) {
    $html = '
    <div class="card card-inverse card-primary">
    <div class="card-block">
    <h4 class="card-title"><i class="fa fa-user-circle" aria-hidden="true"></i> '.$userdetails['fullname'].'</h4>
    </div>
    </div>';
    return $html;
}

function ncmusergrades_grade_table_open() {
    return '<table class="table">
    <thead>
      <tr>
        <th>Assignement Name</th>
        <th>Category</th>
        <th>Mark</th>
        <th>(Weight)</th>
        <th>Grade (%)</th>
      </tr>
    </thead><tbody>';
}

function ncmusergrades_grade_table_close() {
    return '</tbody></table>';
}