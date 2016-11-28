# login.feature
  Feature: User login with e-mail/password
    In order to get access to resources I have in current system
    As a registered user
    I want to login using a username/password authentication pair

  @ci
  Scenario: Invalid credentials
    Given I am on "sign-in"
    And I fill in the following:
      |E-mail address|some_unknown_user|
      |Password      |invalid          |
    When I press "Sign in"
    Then I should see "Invalid credentials"

  @ci
  Scenario: Protected resource should redirect to sign in page
    Given I am on "profile"
    Then I should see "Sign in" in the "h1" element

  @ci
  Scenario: Protected resource access try is kept until valid sign-in process
    Given I am on "profile"
    And I fill in the following:
      |E-mail address|jon.doe@example.com|
      |Password      |123456|
    When I press "Sign in"
    Then the "Public e-mail address" field should contain "jon.doe@example.com"