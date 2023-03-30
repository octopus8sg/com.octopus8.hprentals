<?php

use CRM_Hprentals_Utils as U;
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Setup extends CRM_Core_Form
{

    public function buildQuickForm()
    {
//        U::writeLog("start conf");
        $textsize = ['size' => 77];
        $this->add('checkbox', U::TEST_MODE['slug'], U::TEST_MODE['name']);
        $this->add('static', U::TEST_MODE['slug'] . "_description", U::TEST_MODE['slug'], U::TEST_MODE['description']);

        $this->add('checkbox', U::SAVE_LOG['slug'], U::SAVE_LOG['name']);
        $this->add('static', U::SAVE_LOG['slug'] . "_description", U::SAVE_LOG['slug'], U::SAVE_LOG['description']);


        $this->addButtons([
            [
                'type' => 'submit',
                'name' => E::ts('Submit'),
                'isDefault' => TRUE,
            ],
        ]);
        $this->assign('elementNames', $this->getRenderableElementNames());
        parent::buildQuickForm();
    }

    public function setDefaultValues()
    {
        $defaults = [];
        $settings = CRM_Core_BAO_Setting::getItem(U::SETTINGS_NAME, U::SETTINGS_SLUG);
        U::writeLog($settings, "before save");
        if (!empty($settings)) {
            $defaults = $settings;
        }
        $this->_defaults = $defaults;
        return $defaults;
    }

    /**
     * Getter for $_defaultValues.
     *
     * @return array
     */
    public function getDefaultValues()
    {
        return $this->_defaults;
    }

    public function postProcess()
    {
        $values = $this->exportValues();
        $defaults = $this->getDefaultValues();
        $newTestMode = $values[U::TEST_MODE['slug']];
        $oldTestMode = $defaults[U::TEST_MODE['slug']];
        $settings[U::SAVE_LOG['slug']] = $values[U::SAVE_LOG['slug']];
        $settings[U::TEST_MODE['slug']] = $newTestMode;
        if ($newTestMode) {
            U::createDefaultExpenses();
            U::createDefaultMethods();
            U::create_fake_individuals();
            U::create_faker_rentals();
            U::writeLog('fakers come');
            CRM_Core_Session::setStatus(E::ts('Fake contacts added', ['domain' => 'com.octopus8.hprentals']), 'Configuration Updated', 'success');
        }
        if (!$newTestMode) {
            U::delete_faker_contacts();
            U::writeLog('fakers go');
            CRM_Core_Session::setStatus(E::ts('Fake contacts removed', ['domain' => 'com.octopus8.hprentals']), 'Configuration Updated', 'success');
        }
        U::writeLog($settings, "after_submit");
        $s = CRM_Core_BAO_Setting::setItem($settings, U::SETTINGS_NAME, U::SETTINGS_SLUG);
//        U::writeLog($s);

        CRM_Core_Session::setStatus(E::ts('Hprentals Settings Saved', ['domain' => 'com.octopus8.hprentals']), 'Configuration Updated', 'success');
        parent::postProcess();
    }

    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
    public function getRenderableElementNames()
    {
        // The _elements list includes some items which should not be
        // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
        // items don't have labels.  We'll identify renderable by filtering on
        // the 'label'.
        $elementNames = array();
        foreach ($this->_elements as $element) {
            $label = $element->getLabel();
            if (!empty($label)) {
                $elementNames[] = $element->getName();
            }
        }
        return $elementNames;
    }


}




