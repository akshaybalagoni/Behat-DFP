<?php
/**
 * @file
 */
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
/**
 * Defines application features for admin-form.feature.
 */
class AdminFormContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * @When I open the DFP Settings form
     */
    public function iOpenTheDfpSettingsForm() {
        $this->visitPath('admin/config/media/dfp');
        $this->assertSession()->responseContains('DFP Settings');
    }
    /**
     * @When I set values for all form fields
     */
    public function iSetValuesForAllFormFields() {
        $page = $this->getSession()->getPage();
        $page->findField('adfuel_js')->setValue('http://i.cdn.turner.com/ads/adfuel/adfuel-1.1.0.js');
        $page->findField('ais_js')->setValue('http://i.cdn.turner.com/ads/adfuel/ais/cnnarabic-ais.js');
        $page->findField('registry_index')->setValue('http://i.cdn.turner.com/ads/cnn_arabic/index.txt');
        $page->findField('singleton_base')->setValue('https://i.cdn.turner.com/ads/tbs/index.txt');
        $page->findField('ros_enabled')->check();
        $page->findField('ros_registry')->setValue('http://i.cdn.turner.com/ads/cnn_arabic/cnnarabic_ros_main.js');
    }
    /**
     * @When I submit the form
     */
    public function iSubmitTheForm() {
        $this->getSession()->getPage()->pressButton('Save configuration');
    }
    /**
     * @Then I should see a confirmation message
     */
    public function iShouldSeeAConfirmationMessage() {
        $this->assertSession()->pageTextContains('The configuration options have been saved.');
    }
}