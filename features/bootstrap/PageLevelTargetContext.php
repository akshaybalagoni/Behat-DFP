<?php

use Drupal\draco_dfp\Entity\AdKeyword;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
/**
 * Defines application features for page-level-target.feature.
 */
class PageLevelTargetContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * A sample term used in steps.
     *
     * @var \Drupal\taxonomy\Entity\Term
     */
    protected $sampleTerm;
    /**
     * A sample AdKeyword used in steps.
     *
     * @var \Drupal\draco_dfp\Entity\AdKeyword
     */
    protected $adKeyword;
    /**
     * @When I set the Site Registry index to :arg1 at the DFP settings
     */
    public function iSetTheSiteRegistryIndexToAtTheDfpSettings($arg1) {
        $this->visitPath('/admin/config/media/dfp');
        $page = $this->getSession()->getPage();
        $page->findField('registry_index')->setValue($arg1);
        $this->getSession()->getPage()->pressButton('Save configuration');
        $this->assertSession()->pageTextContains('The configuration options have been saved.');
    }
    /**
     * @When I pull new registry files by updating the registry index
     */
    public function iPullNewRegistryFilesByUpdatingTheRegistryIndex() {
        $this->visitPath('/admin/config/media/dfp/registry_file');
        $this->getSession()->getPage()->clickLink('Update registry index');
        $this->getSession()->getPage()->pressButton('Import registry files');
        $this->assertSession()->pageTextMatches('/Imported \d+ registry files/');
    }
    /**
     * @When I create an ad context that uses :arg1 on the :arg2 path
     */
    public function iCreateAnAdContextThatUsesOnThePath($arg1, $arg2) {
        $this->visitPath('admin/config/media/dfp/ad_context/add');
        // Fill out the form to create an Ad Context.
        $page = $this->getSession()->getPage();
        $label_field = $page->findField('label');
        // Set the value for the field, triggering the machine name update.
        $label_field->setValue('BDD Sample Ad Context');
        // Wait the set timeout for fetching the machine name.
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").text() == "bdd_sample_ad_context"');
        // Fill out the other required fields.
        $page->selectFieldOption('registry_file', $arg1);
        $page->selectFieldOption('weight', 5);
        $page->pressButton('Next');
        // Set the path condition.
        $page = $this->getSession()->getPage();
        $page->selectFieldOption('conditions', 'request_path');
        $page->pressButton('Add Condition');
        $this->getSession()->wait(5000, 'jQuery(\'textarea[name="pages"]\').length === 1');
        // Fill out the form and submit it.
        $this->getSession()->evaluateScript('jQuery(\'textarea[name="pages"]\').val(\'' . $arg2 . '\')');
        $this->getSession()->evaluateScript('jQuery(\'input[value="Save"]\').trigger(\'mousedown\')');
        // Wait for the popup to close.
        $this->getSession()->wait(5000, 'typeof jQuery !== "undefined" && jQuery(\'#configured-conditions tbody tr\').text().indexOf(\'Return true on the following pages: ' . $arg2 . '\') !== -1');
        $this->assertSession()->pageTextContains('Return true on the following pages: ' . $arg2);
        $page->pressButton('Finish');
    }
    /**
     * @When I add an Ad Keyword field to the page content type
     */
    public function iAddAnAdKeywordFieldToThePageContentType() {
        $this->visitPath('/admin/structure/types/manage/page/fields/add-field');
        $page = $this->getSession()->getPage();
        $page->findField('new_storage_type')->setValue('draco_dfp_ad_keyword');
        $page->findField('label')->setValue('BDD Ad Keyword');
        // Wait for the AJAX request that sets the machine name.
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").text() == "field_bdd_ad_keyword"');
        $page->pressButton('Save and continue');
        $page->pressButton('Save field settings');
        $page->pressButton('Save settings');
        $this->assertSession()->pageTextContains('Saved BDD Ad Keyword configuration.');
    }
    /**
     * @When at the DFP settings form I set Ad targeting to :arg1
     */
    public function atTheDfpSettingsFormISetAdTargetingTo($arg1) {
        $this->visitPath('/admin/config/media/dfp');
        $page = $this->getSession()->getPage();
        $page->findField('ad_targeting_id')->setValue($arg1);
        $page->pressButton('Save configuration');
        $this->assertSession()->pageTextContains('The configuration options have been saved.');
    }
    /**
     * @When I create an AdKeyword entity with the label :arg1
     */
    public function iCreateAnAdkeywordEntityWithTheLabel($arg1) {
        $this->adKeyword = AdKeyword::create([
            'label' => $arg1,
            'id' => $arg1,
        ]);
        $this->adKeyword->save();
    }
    /**
     * @When I create an article with :arg1 as the ad keyword
     */
    public function iCreateAnArticleWithAsTheAdKeyword($arg1) {
        $this->visitPath('/node/add/page');
        $page = $this->getSession()->getPage();
        $page->findField('title[0][value]')->setValue('BDD sample page');
        $page->findField('field_bdd_ad_keyword[0][target_id]')->setvalue($arg1 . ' (' . $this->adKeyword->id() . ')');
        $page->pressButton('Save and publish');
        $this->assertSession()->pageTextContains('Basic page BDD sample page has been created.');
    }
    /**
     * @Then I should see an ad with ad targeting set to :target_key and a keyword set to :target
     */
    public function iShouldSeeAnAdWithAdTargetingSetToTargetKeyAndKeywordSetToTarget($target_key, $target) {
        $this->getSession()->wait(3000, 'jQuery(\'#ad_rect_atf_01.adfuel-rendered\').length === 1');
        $actual_target_key = $this->getSession()->evaluateScript('return drupalSettings.draco_dfp.ad_manager.target_key;');
        if ($target_key != $actual_target_key) {
            throw new Exception(sprintf('Expected target_key "%s" not found. Actual "%s"', $target_key, $actual_target_key));
        }
        $actual_target = $this->getSession()->evaluateScript('return drupalSettings.draco_dfp.ad_manager.target;');
        if ($target != $actual_target) {
            throw new Exception(sprintf('Expected target "%s" not found. Actual "%s"', $target, $actual_target));
        }
    }
    /**
     * @Then I should be able to remove the Ad Keyword field
     *
     * Deleting this field programmatically fails, so we need to do it in a step.
     */
    public function iShouldBeAbleToRemoveTheAdKeywordField() {
        $this->visitPath('admin/structure/types/manage/page/fields/node.page.field_bdd_ad_keyword');
        $this->getSession()->getPage()->clickLink('Delete');
        $this->getSession()->getPage()->pressButton('Delete');
        $this->assertSession()->pageTextContains('The field BDD Ad Keyword has been deleted from the Basic page content type.');
    }
    /**
     * Removes the sample ad keyword.
     *
     * @AfterScenario
     */
    public function deleteSampleAdKeyword() {
        $ad_keyword = \Drupal::entityTypeManager()->getStorage('ad_keyword')->load('bilbo');
        if (!empty($ad_keyword)) {
            $ad_keyword->delete();
        }
    }
}