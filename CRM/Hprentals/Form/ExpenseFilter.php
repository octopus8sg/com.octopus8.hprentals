<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_ExpenseFilter extends CRM_Core_Form
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
            aoData.push({ "name": "expense_id",
                "value": $('#expense_id').val() });
            aoData.push({ "name": "expense_name",
                "value": $('#expense_name').val() });
         */
        // ID or Code
        // Name

        $this->add(
            'text',
            'expense_id',
            ts('Expense ID'),
            ['size' => 28, 'maxlength' => 128]);

        $this->add(
            'text',
            'expense_name',
            ts('Name'),
            ['size' => 28, 'maxlength' => 128]);

    }

}
