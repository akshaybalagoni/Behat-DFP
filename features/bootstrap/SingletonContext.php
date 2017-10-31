<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ElementNotFoundException;
use Drupal\DrupalExtension\Context\RawDrupalContext;
/**
 * Defines application features for singleton.feature.
 *
 * @codingStandardsIgnoreStart
 */
class SingletonContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * @BeforeScenario
     */
    public function setSingletonBase() {
        $this->getDriver()->getCore()->configSet('draco_dfp.settings','singleton_base', 'https://i.cdn.turner.com/ads/tbs/singles/');
        $this->getDriver()->getCore()->clearCache();
    }
    /**
     * @When I visit the demo ads page
     */
    public function iVisitTheDemoAdsPage() {
        $this->visitPath('/draco-dfp/display-ads');
    }
    /**
     * @Then I should see singleton ad tags
     */
    public function iShouldSeeSingletonAdTags() {
        // @see http://i.cdn.turner.com/ads/tbs/singles/tbs_shows_angietribeca.js
        $this->assertSession()->elementExists('css', '#ad_mod_d41f912ef');
    }
    /**
     * @Then I should not see singleton ad tags
     */
    public function iShouldNotSeeSingletonAdTags() {
        try {
            $this->iShouldSeeSingletonAdTags();
        }
        catch (ElementNotFoundException $e) {
            return;
        }
    }
    /**
     * @When I click on the AJAX singleton link
     */
    public function iClickOnTheAjaxSingletonLink() {
        $this->getSession()->evaluateScript('jQuery(\'.dropbutton-toggle\').trigger(\'click\')');
    }
    /**
     * @When I set an invalid singleton base
     */
    public function iSetAnInvalidSingletonBase() {
        $this->getDriver()->getCore()->configSet('draco_dfp.settings','singleton_base', 'https://i.cdn.turner.com/ads/tbs/singles/does-not-exist');
        $this->getDriver()->getCore()->clearCache();
    }
}