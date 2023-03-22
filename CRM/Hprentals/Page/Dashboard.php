<?php
use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_Page_Dashboard extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Financial Dashboard'));

      // link for datatables
      $urlQry['snippet'] = 4;
      $invoice_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_invoice', $urlQry, FALSE, NULL, FALSE);
      $payment_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_payment', $urlQry, FALSE, NULL, FALSE);
      $rental_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_rental', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
      $sourceUrl['invoice_source_url'] = $invoice_source_url;
      $sourceUrl['payment_source_url'] = $payment_source_url;
      $sourceUrl['rental_source_url'] = $rental_source_url;
      $this->assign('useAjax', true);
      CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

      // controller form for ajax search
      $controller_data = new CRM_Core_Controller_Simple(
          'CRM_Hprentals_Form_DashboardFilter',
          ts('Tenant Filter'),
          NULL,
          FALSE, FALSE, TRUE
      );
      $controller_data->setEmbedded(TRUE);
      $controller_data->run();

    // Example: Assign a variable for use in a template
    $this->assign('last_month', date('M Y'));

    parent::run();
  }

}
