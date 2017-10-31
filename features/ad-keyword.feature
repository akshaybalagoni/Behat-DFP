@javascript @api
Feature: Ad keywords
  In order manage ad keywords
  As an authenticated user
  I need to be able to list, create, edit, and delete ad keywords

  Scenario: Manage ad keywords
    Given I am logged in as a user with the "administer draco dfp,view the administration theme" permissions
    When I open the ad keywords listing
    And I create an ad keyword
    And I change the label of the ad keyword
    And I delete the ad keyword
    Then I should see a confirmation message with the deleted ad keyword