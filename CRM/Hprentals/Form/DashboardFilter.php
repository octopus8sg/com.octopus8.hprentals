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

    }

}
