<?php
use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_BAO_RentalsService extends CRM_Hprentals_DAO_RentalsService {

  /**
   * Create a new RentalsService based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Hprentals_DAO_RentalsService|NULL
   *
  public static function create($params) {
    $className = 'CRM_Hprentals_DAO_RentalsService';
    $entityName = 'RentalsService';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
