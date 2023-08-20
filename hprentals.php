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
//    U::writeLog($params, 'hprentals_civicrm_pre para');
//    U::writeLog($objectName, 'hprentals_civicrm_pre onjne');
//    U::writeLog($op, 'hprentals_civicrm_pre op');

    U::beforeSavingDo($op, $objectName, $params);

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
    $menu_items = U::getMenuItems();
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
function hprentals_civicrm_tabset($path, &$tabs, $context)
{
    if ($path === 'civicrm/contact/view') {
        /**
         * Implementation of hook_civicrm_tabset
         * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset
         */
                // add a tab to the contact summary screen
                $contactId = $context['contact_id'];
                $url = CRM_Utils_System::url('civicrm/rentals/tab', ['cid' => $contactId, 'reset' => 1]);

                $myEntities = \Civi\Api4\RentalsRental::get()
                    ->selectRowCount()
                    ->addWhere('tenant_id', '=', $contactId)
                    ->execute();

                $tabs[] = array(
                    'id' => 'contact_rentals_tab',
                    'url' => $url,
                    'count' => $myEntities->count(),
                    'title' => E::ts('Rentals'),
                    'weight' => 1000,
                    'icon' => 'crm-i fa-home'
                );
            }
}

/**
 * Implementation of hook_civicrm_tabset
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset
 */
function hprentals_civicrm_summaryActions(&$actions, $contactID)
{
    $url = CRM_Utils_System::url('civicrm/rentals/tab', ['cid' => $contactID, 'reset' => 1]);
    $actions['otherActions']['rentals'] = array(
        'title' => 'Rentals',
        'weight' => 999,
        'ref' => 'record-rentals',
        'key' => 'rentals',
        'href' => $url,
        'class' => 'no-popup',
        'icon' => 'crm-i fa-home',
    );
}

