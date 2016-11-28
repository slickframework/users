Feature: Register new account
  I order to have access to protected resources on this server
  As a site manager
  I want that user must sign up for site accounts

  @ci
  Scenario: Password mismatch
    Given I am on "sign-up"
    And I fill in the following:
      |Your name            |Joane Queen            |
      |E-mail address       |joane.queen@example.com|
      |Password             |12345                  |
      |Confirm your password|12453                  |
    When I press "Sign up"
    Then I should see "The passwords don't match."

  @ci
  Scenario: Malformed e-mail
    Given I am on "sign-up"
    And I fill in the following:
      |Your name            |Joane Queen            |
      |E-mail address       |joane.queenexample.com|
      |Password             |12345                  |
      |Confirm your password|12453                  |
    When I press "Sign up"
    Then I should see "It seems that this is not a valid e-mail address."

  @ci
  Scenario: Existing e-mail
    Given I am on "sign-up"
    And I fill in the following:
      |Your name            |Joane Queen            |
      |E-mail address       |silvam.filipe@gmail.com|
      |Password             |12345                  |
      |Confirm your password|12345                  |
    When I press "Sign up"
    Then I should see "There is already an account with this email address. Did you forgot your password?"

  @ci
  Scenario: Successfull register
    Given I am on "sign-up"
    And I fill in the following:
      |Your name            |Joane Queen            |
      |E-mail address       |joane.queen@example.com|
      |Password             |123456                 |
      |Confirm your password|123456                 |
    When I press "Sign up"
    Then I should see "Sign up completed successfully."