@javascript @api
Feature: Admin form
  In order configure Draco DFP
  As an authenticated user
  I need to be able to set the module's settings via it's admin form.

  Scenario: Fill and submit the administration form
    Given I am logged in as a user with the "administer draco dfp,view the administration theme" permissions
    When I open the DFP Settings form
    And I set values for all form fields
    And I submit the form
    Then I should see a confirmation message