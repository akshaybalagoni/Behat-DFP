<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\node\Entity\Node;
/**
 * Defines application features for run-of-site-ads.feature.
 *
 * @codingStandardsIgnoreStart
 */
class RunOfSiteAdsContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * A page node used for testing.
     *
     * @var \Drupal\Core\Entity\ContentEntityInterface
     */
    protected $node;
    /**
     * @When I activate Run of Site ads
     */
    public function iActivateRunOfSiteAds() {
        $page = $this->getSession()->getPage();
        $page->findField('ros_enabled')->check();
        $page->pressButton('Save configuration');
    }
    /**
     * @When I create a page
     */
    public function iCreateAPage() {
        $this->node = Node::create([
            'type' => 'page',
            'title' => 'BDD Bar node',
        ]);
        $this->node->save();
    }
    /**
     * @Then I should see the ROS registry file URL
     */
    public function iShouldSeeTheRosRegistryFileUrl() {
        $this->visitPath('node/' . $this->node->id());
        $this->assertSession()->responseContains('ad_manager');
    }
    /**
     * @When I deactivate Run of Site ads
     */
    public function iDeactivateRunOfSiteAds() {
        $page = $this->getSession()->getPage();
        $page->findField('ros_enabled')->uncheck();
        $page->pressButton('Save configuration');
    }
    /**
     * @Then I should not see the ROS registry file URL
     */
    public function iShouldNotSeeTheRosRegistryFileUrl() {
        $this->visitPath('node/' . $this->node->id());
        $this->assertSession()->responseNotContains('ad_manager');
    }
}