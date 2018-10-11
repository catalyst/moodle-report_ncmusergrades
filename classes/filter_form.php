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
 * The site outcomes report filter form
 *
 * @package    report_ncmusergrades
 * @author     Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @copyright  2018 Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_ncmusergrades;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

/**
 * Form for site outcomes filters
 *
 * @package    report_ncmusergrades
 * @author     Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @copyright  2018 Nicolas Jourdain <nicolas.jourdain@navitas.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_form extends \moodleform {

    /**
     * Definition of the Mform for filters displayed in the report.
     */
    public function definition() {

        $mform = $this->_form;
        $userid = $this->_customdata['userid'];

        $mform->addElement('text', 'userid', get_string('userid', 'report_ncmusergrades'));
        $mform->setType('userid', PARAM_ALPHANUM);
        $mform->addRule('userid', get_string('rulemsguserid', 'report_ncmusergrades'), 'required', '', 'server', false, false);

        // $options = array(                                                                                                    
        //     'noselectionstring' => get_string('allareas', 'search'),
        //     'placeholder' => 'User search',
        //     // 'ajax' => 'enrol_manual/form-potential-user-selector',
        //     'ajax' => 'report_ncmusergrades/form-user-selector',
        //     'courseid' => 1,
        //     'enrolid' => null                                                            
        // );
        // $mform->addElement('autocomplete', 'userid2', get_string('selectusers', 'enrol_manual'), array(), $options);
        
        // Add a submit button.
        $mform->addElement('submit', 'submitbutton', get_string('submit'));
    }
}