@javascript @api
Feature: Run of site ads
  In order to view Run of Site ads
  As an authenticated user
  I need to be able to activate Run of Site ads and view them in a node

  Scenario: Enable and test Run of Site ads
    Given I am logged in as a user with the "administrator" role
    When I open the DFP Settings form
    And I activate Run of Site ads
    And I create a page
    Then I should see the ROS registry file URL

  Scenario: Disable and test Run of Site ads
    Given I am logged in as a user with the "administrator" role
    When I open the DFP Settings form
    And I deactivate Run of Site ads
    And I create a page
    Then I should not see the ROS registry file URL