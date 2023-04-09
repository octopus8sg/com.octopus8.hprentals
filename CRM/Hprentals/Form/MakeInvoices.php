<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_MakeInvoices extends CRM_Core_Form {
  public function buildQuickForm() {
      Civi::service('angularjs.loader')
          ->addModules('YearsAndMonths');

  }

  public function postProcess() {
    $values = $this->exportValues();
    U::writeLog($values, 'CRM_Hprentals_Form_MakeInvoices');
    parent::postProcess();
  }
}
