<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * RentalsRental.create API specification (optional).
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_rentals_rental_create_spec(&$spec)
{
    // $spec['some_parameter']['api.required'] = 1;
}

/**
 * RentalsRental.create API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_rentals_rental_create($params)
{
    return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'RentalsRental');
}

/**
 * RentalsRental.delete API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_rentals_rental_delete($params)
{
    return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * RentalsRental.get API.
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_rentals_rental_get($params)
{
    $rental_got = _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params, TRUE, 'RentalsRental');
    U::writeLog($rental_got,'civicrm_api3_rentals_rental_get');
    U::writeLog($params,'civicrm_api3_rentals_rental_get params');
    return $rental_got;
}
