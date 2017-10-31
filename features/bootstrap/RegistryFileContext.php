<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
/**
 * Defines application features for registry-file.feature.
 */
class RegistryFileContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * @When I open the list of Registry Files
     */
    public function iOpenTheListOfRegistryFiles() {
        $this->visitPath('/admin/config/media/dfp/registry_file');
    }
    /**
     * @When I fill out the registry files form and submit it
     */
    public function iFillOutTheRegistryFilesFormAndSubmitIt() {
        // The label field generates a machine name via AJAX. We therefore have to
        // fill out the form in two steps before submitting it.
        $page = $this->getSession()->getPage();
        $label_field = $page->findField('label');
        $label_field->setValue('BDD Foo');
        $this->getSession()->wait(1000, 'jQuery("#edit-label-machine-name-suffix .machine-name-value").html() == "bdd_foo"');
        $page->findField('url')->setValue('http://www.example.com');
        $page->pressButton('Save');
    }
}