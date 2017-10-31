<?php
/**
 * @file
 */
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
/**
 * Defines application features for ad-context.feature.
 */
class AdContextContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * A sample term used in steps.
     *
     * @var \Drupal\taxonomy\Entity\Term
     */
    protected $sampleTerm;
    /**
     * @When I start creating an ad context
     */
    public function iStartCreatingAnAdContext() {
        // Navigate to the form to add an Ad Context.
        $this->getSession()->resizeWindow(1440, 900, 'current');
        $this->visitPath('admin/config');
        $this->getSession()->getPage()->clickLink('Display Ads settings');
        $this->assertSession()->pageTextContains('DFP settings');
        $this->getSession()->getPage()->clickLink('Ad contexts');
        $this->getSession()->getPage()->clickLink('Add Ad context');
        $this->assertSession()->addressEquals('/admin/config/media/dfp/ad_context/add');
        // Fill out the form to create an Ad Context.
        $page = $this->getSession()->getPage();
        $label_field = $page->findField('label');
        // Set the value for the field, triggering the machine name update.
        $label_field->setValue('BDD Sample Ad Context');
        // Wait the set timeout for fetching the machine name.
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").html() == "bdd_sample_ad_context"');
        // Fill out the other required fields.
        $page->selectFieldOption('registry_file', 'bdd_sample_registry_file');
        $page->selectFieldOption('weight', 5);
        $page->pressButton('Next');
        // Check that conditions can be added at the Conditions step.
        $this->assertSession()->pageTextContains('No conditions have been configured. At least one condition is needed to continue.');
    }
    /**
     * @When I set a request path condition of \/foo
     */
    public function iSetARequestPathConditionOfFoo() {
        $page = $this->getSession()->getPage();
        $page->selectFieldOption('conditions', 'request_path');
        $page->pressButton('Add Condition');
        $this->getSession()->wait(5000, 'jQuery(\'textarea[name="pages"]\').length === 1');
        // Fill out the form and submit it.
        $this->getSession()->evaluateScript('jQuery(\'textarea[name="pages"]\').val(\'/bar\')');
        $this->getSession()->evaluateScript('jQuery(\'input[value="Save"]\').trigger(\'mousedown\')');
        // Wait for the popup to close.
        $this->getSession()->wait(5000, 'typeof jQuery !== "undefined" && jQuery(\'#configured-conditions tbody tr\').text().indexOf(\'Return true on the following pages: /bar\') !== -1');
        $this->assertSession()->pageTextContains('Return true on the following pages: /bar');
        $this->getSession()->getPage()->pressButton('Finish');
        $this->assertSession()->pageTextContains('Saved the BDD Sample Ad Context Ad context.');
    }
    /**
     * @When I create an article A with the path \/foo
     */
    public function iCreateAnArticleAWithThePathFoo() {
        $node = Node::create([
            'type' => 'article',
            'title' => 'BDD Bar node',
            'path' => ['alias' => '/bar'],
        ]);
        $node->save();
    }
    /**
     * @Then When I open article A I should see the ad context
     */
    public function whenIOpenArticleAIShouldSeeTheAdContext() {
        // Open the node and test that the Registry File was loaded.
        $this->visitPath('bar');
        $this->assertSession()->responseContains('s.cdn.turner.com');
    }
    /**
     * @When I create a taxonomy term A
     */
    public function iCreateATaxonomyTermA() {
        // Create a term for the Tags vocabulary.
        $this->sampleTerm = Term::create([
            'name' => 'BDD Term',
            'id' => 'bdd_term',
            'vid' => 'tags',
        ]);
        $this->sampleTerm->save();
    }
    /**
     * @When I create an ad context with a taxonomy condition of A
     */
    public function iCreateAnAdContextWithATaxonomyConditionOfA() {
        // Navigate to the form to add an Ad Context.
        $this->visitPath('/admin/config/media/dfp/ad_context/add');
        // Fill out the form to create an Ad Context.
        $page = $this->getSession()->getPage();
        $label_field = $page->findField('label');
        // Set the value for the field, triggering the machine name update.
        $label_field->setValue('BDD Sample Ad Context B');
        // Wait the set timeout for fetching the machine name.
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").html() == "bdd_sample_ad_context_b"');
        // Fill out the other required fields.
        $page->selectFieldOption('registry_file', 'bdd_sample_registry_file');
        $page->selectFieldOption('weight', 5);
        $page->pressButton('Next');
        // Set the taxonomy condition to the term BDD Term.
        $page = $this->getSession()->getPage();
        $page->selectFieldOption('conditions', 'taxonomy_reference');
        $page->pressButton('Add Condition');
        $this->getSession()->wait(5000, 'jQuery(\'select[name="field_name"]\').length === 1');
        // Fill out the form and submit it.
        $session = $this->getSession();
        $session->evaluateScript('jQuery(\'select[name="field_name"]\').val(\'field_tags\')');
        $session->evaluateScript('jQuery(\'input[name="field_value"]\').val(\'BDD Term (' . $this->sampleTerm->id() . ')\')');
        $session->evaluateScript('jQuery(\'input[value="Save"]\').trigger(\'mousedown\')');
        // Wait for the popup to close.
        $this->getSession()->wait(5000, 'typeof jQuery !== "undefined" && jQuery(\'#configured-conditions tbody tr\').text().indexOf(\'Matches term BDD Term in field field_tags for the current node\') !== -1');
        $this->assertSession()->pageTextContains('Matches term BDD Term in field field_tags for the current node.');
        // Complete the wizard.
        $this->getSession()->getPage()->pressButton('Finish');
        $this->assertSession()->pageTextContains('Saved the BDD Sample Ad Context B Ad context.');
    }
    /**
     * @When I create an article X with the tag A
     */
    public function iCreateAnArticleXWithTheTagA() {
        $node = Node::create([
            'type' => 'article',
            'title' => 'BDD Baz node',
            'path' => ['alias' => '/baz'],
            'field_tags' => ['target_id' => $this->sampleTerm->id()],
        ]);
        $node->save();
    }
    /**
     * @When I don't add a condition
     */
    public function iDontAddACondition() {
        $this->getSession()->getPage()->pressButton('Finish');
    }
    /**
     * @Then When I open article X I should see the ad context
     */
    public function whenIOpenArticleXIShouldSeeTheAdContext() {
        $this->visitPath('baz');
        $this->assertSession()->responseContains('s.cdn.turner.com');
    }
    /**
     * @When I open the Ad Contexts list
     */
    public function iOpenTheAdContextsList() {
        $this->visitPath('admin/config/media/dfp/ad_context');
    }
    /**
     * @When I delete the ad context
     */
    public function iDeleteTheAdContext() {
        // This line expands the mutton so the Delete operation becomes visible.
        $this->getSession()->evaluateScript('jQuery(\'.dropbutton-toggle\').trigger(\'click\')');
        $this->getSession()->getPage()->clickLink('Delete');
        $this->getSession()->getPage()->pressButton('Delete');
    }
    /**
     * @Then I should not see it at the Ad Contexts list
     */
    public function iShouldNotSeeItAtTheAdContextsList() {
        $this->assertSession()->pageTextContains('The ad context BDD Sample Ad Context has been deleted.');
        $this->assertSession()->pageTextContains('There is no Ad context yet.');
    }
    /**
     * @When I edit the User Account block form
     */
    public function iEditTheUserAccountBlockForm() {
        $this->visitPath('admin/structure/block/manage/bartik_account_menu');
    }
    /**
     * @When I visit the front page
     */
    public function iVisitTheFrontPage() {
        $this->visitPath('/');
    }
}