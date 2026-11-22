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
 * Settings for a phone number profile field.
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Admin form part: the default country calling code (param1).
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_define_phone extends profile_define_base {
    #[\Override]
    public function define_form_specific($form) {
        $form->addElement('text', 'param1', get_string('defaultcountry', 'profilefield_phone'), ['size' => 5]);
        $form->setType('param1', PARAM_TEXT);
        $form->addHelpButton('param1', 'defaultcountry', 'profilefield_phone');
    }

    #[\Override]
    public function define_validate_specific($data, $files) {
        $errors = [];
        $code = ltrim(trim((string)($data->param1 ?? '')), '+');
        if ($code !== '' && !preg_match('/^[1-9][0-9]{0,2}$/', $code)) {
            $errors['param1'] = get_string('error_countrycode', 'profilefield_phone');
        }
        return $errors;
    }
}
