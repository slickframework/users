# edit profile feature
  Feature: Edit profile after successful sign in
    In order to change/update my account information
    As a sign in user
    I want to change the full name or e-mail address

    notes:
      - Changing the e-mail address needs to check if its already in the
      database for other user;
      - Changing the e-mail address set account to be unconfirmed
      - An e-mail is issued to the user for e-mail confirmation

  Background: Running tests as a sign in user
    Given I am on "profile"
    When I fill in the following:
      |E-mail address|jon.doe@example.com|
      |Password      |123456|
    And I press "Sign in"
    Then I should see "Public profile"

  Scenario: Change full name
    Given I fill in "Name" with "Jon Doe"
    When I press "Update profile"
    Then I should see "Your profile information was successfully updated."
    And the "Name" field should contain "Jon Doe"

  Scenario: Entering existing e-mail
    Given I fill in "Public e-mail address" with "silvam.filipe@gmail.com"
    When I press "Update profile"
    Then I should see "The e-mail address already exists. Is it yours?"
    And I should see "Your profile was not updated. Please check the errors below and try again."