<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_DashboardFilter extends CRM_Core_Form
{

    /**
     * Function to get _cid for tabs
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        parent::preProcess();
    }


    /**
     * calls all the functions to add controls depending on _cid,
     * @throws CRM_Core_Exception
     */
    public function buildQuickForm()
    {


        $this->filter();

        $this->assign('suppressForm', FALSE);
        parent::buildQuickForm();
    }

    function filter()
    {
        /*
         *
            aoData.push({ "name": "dashboard_id",
                "value": $('#dashboard_id').val() });
            aoData.push({ "name": "dashboard_name",
                "value": $('#dashboard_name').val() });
         */
        // ID or Code
        // Name
        $props = ['create' => false,
            'multiple' => false,
            'class' => 'huge',
            'api' =>
                ['params' =>
                    ['contact_type' => 'Individual']
                ],
            'select' => ['minimumInputLength' => 0],
        ];

        {
            $this->addEntityRef('tenant_id', E::ts('Tenant (Contact)'),
                $props,
                false);
        }
        $rental_months = U::getRentalMonths();
        $hierSelectArrays = U::getHierSelectArrays($rental_months);

        $years = $hierSelectArrays['years'];

//        $yearsAssoc = array_combine($years, $years);
        $months = $hierSelectArrays['months'];
        $sel =& $this->addElement('hierselect', 'months', 'Choose Rental Months', null, ['style' => 'width: 200px;'], $defaultOptionValue);
        $sel->setMainOptions($years);
        $sel->setSecOptions($months);

        $defaultYear = null;
        $defaultMonth = null;

        if (!empty($months)) {
            // Extract the last year from the array keys
            $yearsKeys = array_keys($months);
            $defaultYear = end($yearsKeys);

            // Extract the last month from the array values
            $monthsOfYear = end($months);
            $monthsKeys = array_keys($monthsOfYear);
            $defaultMonth = end($monthsKeys);
        }

// Now you can set the default value for the hierselect element
        $this->setDefaults(['months' => [$defaultYear, $defaultMonth]]);


    }

}
