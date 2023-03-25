<?php
use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_BAO_RentalsInvoice extends CRM_Hprentals_DAO_RentalsInvoice {

  /**
   * Create a new RentalsInvoice based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Hprentals_DAO_RentalsInvoice|NULL
   *
  public static function create($params) {
    $className = 'CRM_Hprentals_DAO_RentalsInvoice';
    $entityName = 'RentalsInvoice';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */
    public static function &getFields($checkPermission = FALSE) {
        $fields = [];
        $fields = array_merge($fields, CRM_Hprentals_DAO_RentalsInvoice::fields());
        $fields['tenant_id']['html'] = [
            'type' => 'EntityRef',
            'label' => ts("Tenant"),
        ];
        return $fields;
    }
}
