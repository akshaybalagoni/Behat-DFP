@javascript @api
Feature: Registry file
  In order to manage registry files
  As an authenticated user
  I need to be able to create registry file entities and run the import batch job.

  Scenario: Create a registry file entity
    Given I am logged in as a user with the "administer draco dfp,view the administration theme" permissions
    When I open the list of Registry Files
    And I click "Add Registry file"
    And I fill out the registry files form and submit it
    Then I should see the text "Saved the BDD Foo Registry file"

  Scenario: Import registry files
    Given I am logged in as a user with the "administer draco dfp,view the administration theme" permissions
    When I create a registry file
    And I open the list of Registry Files
    And I click "Update registry index"
    And I press the "Import registry files" button
    Then I should see text matching "Imported \d+ registry files"