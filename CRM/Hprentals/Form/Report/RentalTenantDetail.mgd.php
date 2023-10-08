<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Hprentals_Form_Report_RentalTenantDetail',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Tenant Detail',
      'description' => 'HP Rental TenantDetail (com.octopus8.hprentals)',
      'class_name' => 'CRM_Hprentals_Form_Report_RentalTenantDetail',
      'report_url' => 'com.octopus8.hprentals/rentaltenantdetail',
      'component' => '',
      'grouping' => 'Rentals',
    ],
  ],
];
