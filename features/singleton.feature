@javascript @api
Feature: Singletons
  In order to render singletons
  As an anonymous user user
  I need to be able to open a page with singleton tags and see the rendered ads.

  Scenario: View singleton ads on page load
    Given I am an anonymous user
    When I visit the demo ads page
    Then I should see singleton ad tags

  Scenario: View AJAX singleton
    Given I am an anonymous user
    When I visit the demo ads page
    And I click on the AJAX singleton link
    Then I should see singleton ad tags

  Scenario: No ads are shown when the singleton cannot be loaded
    Given I set an invalid singleton base
    And I am an anonymous user
    When I visit the demo ads page
    Then I should not see singleton ad tags

  Scenario: No AJAX ads are shown when the singleton cannot be loaded
    Given I set an invalid singleton base
    And I am an anonymous user
    When I visit the demo ads page
    And I click on the AJAX singleton link
    Then I should not see singleton ad tags