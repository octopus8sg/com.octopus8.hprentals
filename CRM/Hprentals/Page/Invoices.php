<?php

use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_Page_Invoices extends CRM_Core_Page
{

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Invoices'));

        Civi::service('angularjs.loader')
            ->addModules('crmHprentals');



        parent::run();
    }

}
