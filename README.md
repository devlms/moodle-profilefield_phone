# Phone number profile field (profilefield_phone)

A user profile field for phone numbers that accepts what people actually type and saves one clean international format (E.164).

- "0555 12 34 56", "+213 555-12-34-56" and "00213 555 12 34 56" are all saved as `+213555123456` when the field's default country code is 213.
- Text that is not a phone number is refused with an example of what to type.
- On the profile the number is a tap-to-call link.
- Leave the default country code empty to require the + format.
- Italy, San Marino and the Vatican keep the leading 0 of landline numbers (06 1234 5678 becomes +390612345678).

Add it in Site administration > Users > User profile fields > Create a new profile field > Phone number. Moodle 4.5 to 5.3.

Built by DevLMS: https://devlms.com
