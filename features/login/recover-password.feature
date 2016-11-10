Feature: Recover password
  In order reset a forgotten password
  As a registered user
  I want to request to recover the password using my e-mail address

  Background: Forgot my password on login screen
    Given I am on "sign-in"
    And I follow "Did you forgot your password?"
    And I fill in "E-mail address" with "jane.doe@example.com"

  Scenario: unknown e-mail address
    Given I fill in "E-mail address" with "someone@example.com"
    When I press "Recover my password"
    Then I should see "We don't have any account with this e-mail address."

  Scenario: invalid e-mail address
    Given I fill in "E-mail address" with "someone.example.com"
    When I press "Recover my password"
    Then I should see "It seems that this is not a valid e-mail address."

  Scenario: fail inserting password
    Given I press "Recover my password"
    And I should receive an e-mail on "jane.doe@example.com" address
    When I follow the token link on the e-mail
    And I fill in "Password" with "1235674"
    And I fill in "Confirm your password" with "1234567"
    And I press "Change my password"
    Then I should see "The passwords don't match."

  Scenario: Recover the password
    Given I press "Recover my password"
    Then I should receive an e-mail on "jane.doe@example.com" address
    When I follow the token link on the e-mail
    And I fill in "Password" with "1234567"
    And I fill in "Confirm your password" with "1234567"
    And I press "Change my password"
    Then I should see "Your password was recovered successfully."
    Given I am on "sign-out"
    And I am on "profile"
    And I fill in the following:
      |E-mail address|jane.doe|
      |Password      |1234567|
    When I press "Sign in"
    Then the "Public e-mail address" field should contain "jon.doe@example.com"