<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

class CRM_Hprentals_Page_RentalsTab extends CRM_Core_Page
{

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Rentals'));
        $cid = CRM_Utils_Request::retrieve('cid', 'String');
        U::writeLog($cid, "cid in tabs");
        Civi::service('angularjs.loader')
            ->addModules('tenantRentalSearch');
        Civi::service('angularjs.loader')
            ->addModules('tenantInvoiceSearch');
        Civi::service('angularjs.loader')
            ->addModules('tenantPaymentSearch');
        $optionalVars = ['contact_id' => $cid];

        $this->assign('myAfformVars', $optionalVars);
        $rentalCount = \Civi\Api4\RentalsRental::get(FALSE)
            ->selectRowCount()
            ->addWhere('tenant_id', '=', $cid)
            ->execute()->count();
        $paymentCount = \Civi\Api4\RentalsPayment::get(FALSE)
            ->selectRowCount()
            ->addWhere('tenant_id', '=', $cid)
            ->execute()->count();
        $invoiceCount = \Civi\Api4\RentalsInvoice::get(FALSE)
            ->selectRowCount()
//            ->addJoin('RentalsRental AS rentals', 'LEFT', ['rental_id', '=', 'rentals.id'])
            ->addWhere('rental_id.tenant_id', '=', $cid)
            ->execute()->count();
        // Example: Assign a variable for use in a template
        $this->assign('rentalCount', $rentalCount);
        $this->assign('invoiceCount', $invoiceCount);
        $this->assign('paymentCount', $paymentCount);
        parent::run();
    }

}
