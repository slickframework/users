Feature: Remember me login
  In order to simplify highly used and protected site
  As a registered user
  I want to set a "remember me" check box and automatically login in subsequent accesses.

  Notes:
    - This login is considered a dirty login
    - A remembered cookie will live for 30 days
    - A new cookie is set every time users sign in with a cookie (old one deleted)
    - Profile change needs password to continue
    - If a token is found but its hash does not match ALL token accounts must be deleted.

  Scenario: Be remembered after browser closes
    Given I am on "profile"
    And I fill in the following:
      |E-mail address|jon.doe@example.com|
      |Password      |123456|
    And I check "Remember me on this computer"
    And I press "Sign in"
    Then the "Public e-mail address" field should contain "jon.doe@example.com"
    When I restart the browser
    And I am on "profile"
    Then the "Public e-mail address" field should contain "jon.doe@example.com"