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

namespace profilefield_phone;

/**
 * Tests for the phone number profile field.
 *
 * @package    profilefield_phone
 * @copyright  2026 DevLMS
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \profile_field_phone
 */
final class field_test extends \advanced_testcase {
    public static function setUpBeforeClass(): void {
        global $CFG;
        parent::setUpBeforeClass();
        require_once($CFG->dirroot . '/user/profile/lib.php');
        require_once($CFG->dirroot . '/user/profile/field/phone/field.class.php');
    }

    public function test_normalise(): void {
        $this->assertSame('+213555123456', \profile_field_phone::normalise('+213 555-12-34-56', ''));
        $this->assertSame('+213555123456', \profile_field_phone::normalise('00213 (555) 12 34 56', ''));
        $this->assertSame('+213555123456', \profile_field_phone::normalise('0555 12 34 56', '213'));
        $this->assertSame('+44207946000', \profile_field_phone::normalise('020 7946 000', '+44'));
        $this->assertNull(\profile_field_phone::normalise('0555 12 34 56', ''), 'National number without a default country');
        $this->assertNull(\profile_field_phone::normalise('+12', ''), 'Too short');
        $this->assertNull(\profile_field_phone::normalise('+1234567890123456', ''), 'Too long');
        $this->assertNull(\profile_field_phone::normalise('call me', '213'));
        $this->assertSame('', \profile_field_phone::normalise('  ', '213'));
        // Italy, San Marino and the Vatican keep the leading 0 of landline numbers after the country code.
        $this->assertSame('+390612345678', \profile_field_phone::normalise('06 1234 5678', '39'));
        $this->assertSame('+393471234567', \profile_field_phone::normalise('347 123 4567', '39'));
    }

    /**
     * A phone field and a field object for a new user.
     *
     * @param string $country default country code
     * @return \profile_field_phone
     */
    private function field(string $country = '213'): \profile_field_phone {
        $this->resetAfterTest();
        $def = $this->getDataGenerator()->create_custom_profile_field(['datatype' => 'phone', 'shortname' => 'mobile',
            'name' => 'Mobile', 'param1' => $country]);
        $user = $this->getDataGenerator()->create_user();
        return new \profile_field_phone($def->id, $user->id);
    }

    public function test_validation_and_saved_format(): void {
        $field = $this->field();
        $bad = (object)['id' => $field->userid, $field->inputname => 'call me'];
        $this->assertArrayHasKey($field->inputname, $field->edit_validate_field($bad));
        $good = (object)['id' => $field->userid, $field->inputname => '0555 12 34 56'];
        $this->assertSame([], $field->edit_validate_field($good));
        $field->edit_save_data($good);
        $this->assertSame('+213555123456', profile_user_record($field->userid)->mobile);
    }

    public function test_display_is_a_tel_link(): void {
        $field = $this->field();
        $field->edit_save_data((object)['id' => $field->userid, $field->inputname => '+213555123456']);
        $field = new \profile_field_phone($field->fieldid, $field->userid);
        $this->assertSame('<a href="tel:+213555123456">+213555123456</a>', $field->display_data());
    }
}
