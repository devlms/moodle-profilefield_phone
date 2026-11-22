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
 * Phone number profile field.
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Accepts numbers as people type them and stores E.164 (+ and 8 to 15 digits).
 *
 * param1 holds the default country calling code used for numbers typed without + or 00.
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class profile_field_phone extends profile_field_base {
    /**
     * Clean a typed number to E.164.
     *
     * @param string $typed what the user typed
     * @param string $country default country calling code, digits (a leading + is allowed), or empty
     * @return string|null the E.164 number, '' when nothing was typed, null when it is not a phone number
     */
    public static function normalise(string $typed, string $country): ?string {
        $typed = trim($typed);
        if ($typed === '') {
            return '';
        }
        if (!preg_match('/^[+0-9\s().\-\/]+$/', $typed)) {
            return null;
        }
        $international = str_starts_with($typed, '+');
        $digits = preg_replace('/\D/', '', $typed);
        if (!$international && str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $international = true;
        }
        if (!$international) {
            $country = ltrim(trim($country), '+');
            if ($country === '' || !ctype_digit($country)) {
                return null;
            }
            $digits = $country . ltrim($digits, '0');
        }
        $length = strlen($digits);
        if ($length < 8 || $length > 15 || $digits[0] === '0') {
            return null;
        }
        return '+' . $digits;
    }

    #[\Override]
    public function edit_field_add($mform) {
        $mform->addElement(
            'text',
            $this->inputname,
            format_string($this->field->name),
            ['type' => 'tel', 'autocomplete' => 'tel', 'size' => 20, 'maxlength' => 32]
        );
        $mform->setType($this->inputname, PARAM_TEXT);
    }

    #[\Override]
    public function edit_validate_field($usernew) {
        $errors = parent::edit_validate_field($usernew);
        $typed = (string)($usernew->{$this->inputname} ?? '');
        if (self::normalise($typed, (string)$this->field->param1) === null) {
            $country = ltrim((string)$this->field->param1, '+');
            $errors[$this->inputname] = $country === ''
                ? get_string('invalid', 'profilefield_phone')
                : get_string('invalid_national', 'profilefield_phone', s($country));
        }
        return $errors;
    }

    #[\Override]
    public function edit_save_data_preprocess($data, $datarecord) {
        if ($data === null) {
            return null;
        }
        return self::normalise((string)$data, (string)$this->field->param1) ?? '';
    }

    #[\Override]
    public function display_data() {
        $number = self::normalise((string)$this->data, '');
        if (!$number) {
            return s((string)$this->data);
        }
        return html_writer::link('tel:' . $number, $number);
    }

    #[\Override]
    public function get_field_properties() {
        return [PARAM_TEXT, NULL_NOT_ALLOWED];
    }
}
