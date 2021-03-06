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
 * The main aojtools configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_aojtools
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_aojtools_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('aojtoolsname', 'aojtools'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'aojtoolsname', 'aojtools');

        // Adding the standard "intro" and "introformat" fields
        $this->add_intro_editor();

        //-------------------------------------------------------------------------------
        // Adding the rest of aojtools settings, spreeading all them into this fieldset
        // or adding more fieldsets ('header' elements) if needed for better logic
        /*$mform->addElement('static', 'label1', 'aojtoolssetting1', 'Your aojtools fields go here. Replace me!');

        $mform->addElement('header', 'aojtoolsfieldset', get_string('aojtoolsfieldset', 'aojtools'));
        $mform->addElement('static', 'label2', 'aojtoolssetting2', 'Your aojtools fields go here. Replace me!');
*/
//ここから
       $mform->addElement('header', 'sessionshdr', get_string('sessions', 'aojtools'));

	$options=array();
        $options[0]  = get_string('period_a', 'aojtools');
        $options[1]  = get_string('period_b', 'aojtools');
        $options[2]  = get_string('period_c', 'aojtools');
        $options[3]  = get_string('period_d', 'aojtools');
        $mform->addElement('select', 'period', get_string('period', 'aojtools'), $options);
	//$mform->addElement('checkbox', 'forceformat', get_string('forceformat', 'wiki'));
        $mform->addElement('date_time_selector', 'starttime', get_string('start', 'aojtools'));
	//$mform->addElement('checkbox', 'forceformat', get_string('forceformat', 'wiki'));
	$mform->addElement('date_time_selector', 'finishtime', get_string('finish', 'aojtools'));
	//問題10問分のやつ
	$mform->addElement('text', 'problem_a', get_string('problem_a', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_b', get_string('problem_b', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_c', get_string('problem_c', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_d', get_string('problem_d', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_e', get_string('problem_e', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_f', get_string('problem_f', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_g', get_string('problem_g', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_h', get_string('problem_h', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_i', get_string('problem_i', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'problem_j', get_string('problem_j', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_1', get_string('point_1', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_2', get_string('point_2', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_3', get_string('point_3', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_4', get_string('point_4', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_5', get_string('point_5', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_6', get_string('point_6', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_7', get_string('point_7', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_8', get_string('point_8', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_9', get_string('point_9', 'aojtools'), array('size'=>'4'));
	$mform->addElement('text', 'point_10', get_string('point_10', 'aojtools'), array('size'=>'4'));

        $this->add_action_buttons();
        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//ここまで
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
