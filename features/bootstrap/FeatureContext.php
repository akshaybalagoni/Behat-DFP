<?php

use Drupal\draco_dfp\Entity\RegistryFile;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * Install Draco DFP Demo module.
     *
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope) {
        /** @var \Drupal\Core\Extension\ModuleHandler $moduleHandler */
        $moduleHandler = \Drupal::service('module_handler');
        if (!$moduleHandler->moduleExists('draco_dfp_demo')) {
            \Drupal::service('module_installer')->install(['draco_dfp_demo']);
        }
    }
    /**
     * @When I create a registry file
     */
    public function iCreateARegistryFile() {
        $registry_file = RegistryFile::create([
            'id' => 'bdd_sample_registry_file',
            'label' => 'Sample registry file',
            'url' => 'https://www.example.com',
        ]);
        $registry_file->save();
    }
    /**
     * Remove sample nodes.
     *
     * @AfterScenario
     */
    public function cleanupNodes() {
        $storage = \Drupal::entityTypeManager()->getStorage('node');
        $ids = $storage->getQuery()->condition('title', 'BDD', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
    /**
     * Removes sample registry files.
     *
     * @AfterScenario
     */
    public function cleanupRegistryFiles() {
        $storage = \Drupal::entityTypeManager()->getStorage('registry_file');
        $ids = $storage->getQuery()->condition('id', 'bdd_', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
    /**
     * Remove sample ad contexts.
     *
     * @AfterScenario
     */
    public function cleanupAdContexts() {
        $storage = \Drupal::entityTypeManager()->getStorage('ad_context');
        $ids = $storage->getQuery()->condition('id', 'bdd_', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
    /**
     * Remove sample vocabularies.
     *
     * @AfterScenario
     */
    public function cleanupVocabularies() {
        $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary');
        $ids = $storage->getQuery()->condition('vid', 'bdd_', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
    /**
     * Remove sample terms.
     *
     * @AfterScenario
     */
    public function cleanupTerms() {
        $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
        $ids = $storage->getQuery()->condition('tid', 'bdd_', 'STARTS_WITH')->execute();
        if (!empty($ids)) {
            $entities = $storage->loadMultiple($ids);
            $storage->delete($entities);
        }
    }
}