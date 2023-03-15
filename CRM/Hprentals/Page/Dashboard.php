<?php
use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_Page_Dashboard extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Financial Dashboard'));

      // link for datatables
      $urlQry['snippet'] = 4;
      $funding_source_url = CRM_Utils_System::url('civicrm/fund/dashboard_ajax_funding', $urlQry, FALSE, NULL, FALSE);
      $contact_source_url = CRM_Utils_System::url('civicrm/fund/dashboard_ajax_contact', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
      $sourceUrl['funding_source_url'] = $funding_source_url;
      $sourceUrl['contact_source_url'] = $contact_source_url;
      $this->assign('useAjax', true);
      CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

      // controller form for ajax search
      $controller_data = new CRM_Core_Controller_Simple(
          'CRM_Funds_Form_CommonSearch',
          ts('Funds Filter'),
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
