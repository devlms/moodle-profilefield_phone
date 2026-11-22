@core_user @profilefield @profilefield_phone
Feature: Phone number profile field
  In order to reach learners by phone
  As an admin
  I need phone numbers saved in one international format however people type them

  Background:
    Given the following "custom profile fields" exist:
      | datatype | shortname | name            | param1 |
      | phone    | whatsapp  | WhatsApp number | 213    |
    And the following "users" exist:
      | username |
      | student1 |

  Scenario: A local number is saved in international format and a wrong one is refused
    Given I log in as "student1"
    And I open my profile in edit mode
    When I set the field "WhatsApp number" to "call me"
    And I press "Update profile"
    And I expand all fieldsets
    Then I should see "Enter a phone number, for example 0555 12 34 56 or +213 555 12 34 56."
    And I set the field "WhatsApp number" to "0555 12 34 56"
    And I press "Update profile"
    And I should see "+213555123456"
    And "+213555123456" "link" should exist
