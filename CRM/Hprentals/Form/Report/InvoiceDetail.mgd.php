<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Hprentals_Form_Report_InvoiceDetail',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Invoice Details',
      'description' => 'HP Rental Invoice Details (com.octopus8.hprentals)',
      'class_name' => 'CRM_Hprentals_Form_Report_InvoiceDetail',
      'report_url' => 'com.octopus8.hprentals/invoicedetail',
      'component_id' => null,
      'grouping' => 'Rentals',
    ],
  ],
];
