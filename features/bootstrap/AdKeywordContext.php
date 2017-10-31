<?php
/**
 * @file
 */
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
/**
 * Defines application features for ad-keyword.feature.
 */
class AdKeywordContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * @When I open the ad keywords listing
     */
    public function iOpenTheAdKeywordsListing() {
        $this->visitPath('admin/config/media/dfp/ad_keyword');
    }
    /**
     * @When I create an ad keyword
     */
    public function iCreateAnAdKeyword() {
        $this->getSession()->getPage()->clickLink('Add Ad keyword');
        $label_field = $this->getSession()->getPage()->findField('label');
        // Set the value for the field, triggering the machine name update.
        $label_field->setValue('BDD Sample Ad Keyword');
        // Wait the set timeout for fetching the machine name.
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").html() == "bdd_sample_ad_keyword"');
        // Fill out the other required fields.
        $this->getSession()->getPage()->pressButton('Save');
        $this->assertSession()->pageTextContains('Created the BDD Sample Ad Keyword Ad keyword.');
    }
    /**
     * @When I change the label of the ad keyword
     */
    public function iChangeTheLabelOfTheAdKeyword() {
        $this->visitPath('admin/config/media/dfp/ad_keyword/bdd_sample_ad_keyword');
        $page = $this->getSession()->getPage();
        $page->findField('label')->setValue('BDD Changed Sample Ad Keyword');
        $page->pressButton('Save');
        $this->assertSession()->pageTextContains('Saved the BDD Changed Sample Ad Keyword Ad keyword.');
    }
    /**
     * @When I delete the ad keyword
     */
    public function iDeleteTheAdKeyword() {
        $this->visitPath('admin/config/media/dfp/ad_keyword/bdd_sample_ad_keyword/delete');
        $this->getSession()->getPage()->pressButton('Delete');
    }
    /**
     * @Then I should see a confirmation message with the deleted ad keyword
     */
    public function iShouldSeeAConfirmationMessageWithTheDeletedAdKeyword() {
        $this->assertSession()->pageTextContains('The ad keyword BDD Changed Sample Ad Keyword has been deleted.');
    }
    /**
     * Remove sample ad keywords.
     *
     * @AfterScenario
     */
    public function cleanupAdKeywords() {
        $storage = \Drupal::entityTypeManager()->getStorage('ad_keyword');
        $ids = $storage->getQuery()->condition('id', 'bdd_', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
}