@javascript @api
Feature: Ad context
  In order to manage ad contexts
  As an authenticated user
  I need to be able to created, edit and delete ad contexts

  Scenario: Add an ad context with no conditions
    Given I am logged in as a user with the "administrator" role
    When I create a registry file
    And I start creating an ad context
    And I don't add a condition
    Then I should see "An ad context requires at least one condition."

  Scenario: Ad context by path condition
    Given I am logged in as a user with the "administrator" role
    When I create a registry file
    And I start creating an ad context
    And I set a request path condition of /foo
    And I create an article A with the path /foo
    Then When I open article A I should see the ad context

  Scenario: Ad context by taxonomy condition
    Given I am logged in as a user with the "administrator" role
    When I create a taxonomy term A
    And I create a registry file
    And I create an ad context with a taxonomy condition of A
    And I create an article X with the tag A
    Then When I open article X I should see the ad context

  Scenario: Delete ad context
    Given I am logged in as a user with the "administrator" role
    When I edit the User Account block form
    And I press the "Save block" button
    And I am not logged in
    And I visit the front page
    Then I should see "Log in"