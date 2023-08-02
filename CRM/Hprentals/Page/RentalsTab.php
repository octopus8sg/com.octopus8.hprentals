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
//        U::writeLog($cid, "cid in tabs");
        Civi::service('angularjs.loader')
            ->addModules('tenantRentalSearch');
        Civi::service('angularjs.loader')
            ->addModules('tenantInvoiceSearch');
        Civi::service('angularjs.loader')
            ->addModules('tenantPaymentSearch');



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
        $total_invoice = U::getInvoiceTotalAmountByTenantId($cid);
        $total_payment = U::getPaymentTotalAmountByTenantId($cid);
        $balance = $total_invoice - $total_payment;
        $optionalVars = [
            'contact_id' => $cid,
            'total_rentals' => $rentalCount,
            'total_invoices' => $invoiceCount,
            'total_payments' => $paymentCount,
            'invoice_sum' => CRM_Utils_Money::format($total_invoice),
            'payment_sum' => CRM_Utils_Money::format($total_payment),
            'balance' => CRM_Utils_Money::format($balance)];
        $this->assign('myAfformVars', $optionalVars);
        $this->assign('rentalCount', $rentalCount);
        $this->assign('invoiceCount', $invoiceCount);
        $this->assign('paymentCount', $paymentCount);
        parent::run();
    }

}
