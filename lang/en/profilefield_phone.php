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
 * Language strings.
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['defaultcountry'] = 'Default country calling code';
$string['defaultcountry_help'] = 'Digits only, for example 213 for Algeria or 44 for the United Kingdom. Numbers typed without a + or 00 prefix are read as numbers in this country (a leading 0 is dropped). Leave empty to require the international format.';
$string['error_countrycode'] = 'Enter 1 to 3 digits, or leave empty.';
$string['invalid'] = 'Enter a phone number with its country code, for example +213 555 12 34 56.';
$string['invalid_national'] = 'Enter a phone number, for example 0555 12 34 56 or +{$a} 555 12 34 56.';
$string['pluginname'] = 'Phone number';
$string['privacy:metadata'] = 'The phone number field stores its value in Moodle core user profile data.';
