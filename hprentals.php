<?php

require_once 'hprentals.civix.php';

// phpcs:disable
use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function hprentals_civicrm_config(&$config)
{
    _hprentals_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function hprentals_civicrm_install()
{
    _hprentals_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function hprentals_civicrm_postInstall()
{
    _hprentals_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function hprentals_civicrm_uninstall()
{
    _hprentals_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function hprentals_civicrm_enable()
{
    _hprentals_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function hprentals_civicrm_disable()
{
    _hprentals_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function hprentals_civicrm_pre($op, $objectName, $id, &$params)
{
    U::addCreatedByModifiedBy($op, $objectName, $params);
    if ($objectName == 'RentalsRental') {
        U::writeLog($params, 'before overlap');
        if($op == 'create' || $op == 'edit' || $op == 'update' )
        $date_from = $params['admission'];
        $date_to = $params['discharge'];
        $rental_id = $params['id'];
        $tenant_id = $params['tenant_id'];
        $existing_rent = U::getOverlappedRents($tenant_id, $date_from, $date_to, $rental_id);
        // If an overlap is found, set a validation error message
        if ($existing_rent > 0) {
            U::showErrorMessage(ts('You already have a rent during this period.'), 'Date Overlap');
            throw new CRM_Core_Exception(ts('You already have a rent during this period.'));
        }
        U::writeLog($params, 'after overlap');
    }

}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function hprentals_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL)
{
    return _hprentals_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function hprentals_civicrm_entityTypes(&$entityTypes)
{
    _hprentals_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function hprentals_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function hprentals_civicrm_navigationMenu(&$menu)
{
    $menu_items = U::MENU;
    foreach ($menu_items as $menu_item) {
        _hprentals_civix_insert_navigation_menu($menu,
            $menu_item['path'],
            $menu_item['menu']);
    }

    _hprentals_civix_navigationMenu($menu);

}

/**
 * Implementation of hook_civicrm_tabset
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset
 */
function __hprentals_civicrm_tabset($path, &$tabs, $context)
{
    if ($path === 'civicrm/contact/view') {
        // add a tab to the contact summary screen
        $contactId = $context['contact_id'];
        $url = CRM_Utils_System::url('civicrm/rentals/search/#?tenant_id=' . $contactId, null, null, null, false);

        $tabs[] = array(
            'id' => 'contact_rentals',
            'url' => $url,
            'title' => E::ts('Rentals'),
            'weight' => 10000,
            'count' => 10,
            'icon' => 'crm-i fa-home',
        );
    }
}

function hprentals_civicrm_angularModules(&$angularModules)
{

//        $angularModules['myAngularModule'] = [
//        'ext' => 'org.example.mymod',
//        'js' => ['js/myAngularModule.js'],
//    ];
//    $angularModules['myBigAngularModule'] = [
//        'ext' => 'org.example.mymod',
//        'requires' => ['ngRoute', 'crmUi', 'myAngularModule'],
//        'basePages' => ['civicrm/a'],
//        'js' => ['ang/myBigAngularModule.js', 'ang/myBigAngularModule/*.js'],
//        'css' => ['css/myAngularModule.css'],
//        'partials' => ['partials/myBigAngularModule'],
//        'permissions' => ['administer CiviCRM'],
//        'settingsCallback' => ['\Civi\Example\Utils', 'getBigAngularData'],
//    ];

}