@javascript @api
Feature: Page level target
  In order to view page level targes
  As an authenticated user
  I need to be able to assign ad keywords to entities

  Scenario: Node with ad keyword
    Given I am logged in as a user with the "administrator" role
    When I set the Site Registry index to "http://i.cdn.turner.com/ads/cnn_arabic/index.txt" at the DFP settings
    And I pull new registry files by updating the registry index
    And I create an ad context that uses "cnnarabic_homepage_registry" on the "/node/*" path
    And I add an Ad Keyword field to the page content type
    And at the DFP settings form I set Ad targeting to "status"
    And I create an AdKeyword entity with the label "bilbo"
    And I create an article with "bilbo" as the ad keyword
    Then I should see an ad with ad targeting set to "status" and a keyword set to "bilbo"
    And I should be able to remove the Ad Keyword field
