# confirm-email.feature
  Feature: E-mail confirmation on profile update
    In order to (re)confirm the e-mail address when I change it
    As a logged in user
    I will receive an e-mail with a link for the new e-mail address from where I can confirm it.

  Background: Running tests as a sign in user
    Given I am on "profile"
    When I fill in the following:
      |E-mail address|jon.doe|
      |Password      |123456|
    And I press "Sign in"
    Then I should see "Public profile"

  @ci
  Scenario: Change the email address
    Given I fill in "Public e-mail address" with "jon.doe@example.org"
    When I press "Update profile"
    Then I should see "Not checked!"
    And I should receive an e-mail on "jon.doe@example.org" address
    When I follow the token link on the e-mail
    Then I should see "Your e-mail address was successfully confirmed."
    When I am on "profile"
    And I should see "Address is confirmed!"

