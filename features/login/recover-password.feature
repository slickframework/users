# recover-password.feature
  Feature: Recover password
    In order reset a forgotten password
    As a registered user
    I want to request to recover the password using my e-mail address

  Scenario: Recover the password
    Given I am on "sign-in"
    And I follow "Did you forgot your password?"
    And I fill in "E-mail address" with "jane.doe@example.com"
    And I press "Recover my password"
    Then I should receive an e-mail on "jane.doe@example.com" address
    And I follow the confirm link on the e-mail
    And I fill in "Password" with "1234567"
    And I fill in "Confirm" with "1234567"
    And I press "Change my password"
    Then I should see "Your password was recovered successfully."