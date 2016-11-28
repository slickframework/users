Feature: Update profile picture
  In order to change my profile picture
  As a registered user
  I want to upload an image, on my profile page

  Background: Logged in user
    Given I am on "profile"
    And I fill in the following:
      |E-mail address|jon.doe|
      |Password      |123456|
    When I press "Sign in"
    Then I should see "Profile picture"


  Scenario: Upload a picture
    Given I attach the file "features/profile/profile-picture.feature" to "avatar"
    When I press "Update picture"
    Then I should see "Your picture was successfully updated."