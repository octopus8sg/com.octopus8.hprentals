<?php

use CRM_Hprentals_Utils as U;
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Setup extends CRM_Core_Form {
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

        return $defaults;
    }

    public function postProcess()
    {
        $values = $this->exportValues();
        $settings[U::SAVE_LOG['slug']] = $values[U::SAVE_LOG['slug']];
        $settings[U::TEST_MODE['slug']] = $values[U::TEST_MODE['slug']];

        U::writeLog($settings, "after_submit");
        $s = CRM_Core_BAO_Setting::setItem($settings, U::SETTINGS_NAME, U::SETTINGS_SLUG);
//        U::writeLog($s);
        CRM_Core_Session::setStatus(E::ts('Hprentals Settings Saved', ['domain' => 'com.octopus8.hprentals']), 'Configuration Updated', 'success');
        $test_mode = U::getTestMode();
        if($test_mode){
            U::createTestExpenes();
            U::createTestMethods();
        }
        parent::postProcess();
    }

    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
    public function getRenderableElementNames() {
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



function create_fake_individuals($num_individuals) {
    $faker = Faker\Factory::create();
    $contact_type = 'Individual';
    $api = civicrm_api3('Contact', 'create');

    // check if the group already exists
    $group_title = 'Faker Contacts';
    $group_params = array('title' => $group_title);
    $group_api = civicrm_api3('Group', 'get', $group_params);
    if ($group_api['is_error']) {
        // handle error
        return $group_api['error_message'];
    }
    if (count($group_api['values']) > 0) {
        // group already exists, use existing group ID
        $group_id = $group_api['values'][0]['id'];
    } else {
        // group doesn't exist, create a new group
        $group_api = civicrm_api3('Group', 'create', $group_params);
        if ($group_api['is_error']) {
            // handle error
            return $group_api['error_message'];
        }
        $group_id = $group_api['id'];
    }

    for ($i = 1; $i <= $num_individuals; $i++) {
        $params = array(
            'contact_type' => $contact_type,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->safeEmail,
            'phone' => $faker->phoneNumber,
            'street_address' => $faker->streetAddress,
            'city' => $faker->city,
            'state_province' => $faker->stateAbbr,
            'postal_code' => $faker->postcode,
            'country' => $faker->country,
        );
        $result = $api->create($params);
        if ($result['is_error']) {
            // handle error
            return $result['error_message'];
        }

        // add contact to the group
        $contact_id = $result['id'];
        $group_contact_api = civicrm_api3('GroupContact', 'create', array(
            'group_id' => $group_id,
            'contact_id' => $contact_id,
        ));
        if ($group_contact_api['is_error']) {
            // handle error
            return $group_contact_api['error_message'];
        }
    }

    // return success message
    return "Created $num_individuals fake Individual entities in CiviCRM and added them to the '$group_title' group.";
}
