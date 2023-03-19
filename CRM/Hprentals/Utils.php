<?php

use CRM_Hprentals_ExtensionUtil as E;

class CRM_Hprentals_Utils
{

    //SETTINGS

    public const SETTINGS_NAME = "Hprentals Settings";
    public const SETTINGS_SLUG = 'hprentals_settings';

    public const SAVE_LOG = [
        'slug' => 'save_log',
        'name' => 'Save Log',
        'description' => "Write debugging output to CiviCRM log file"];

    //PATHS
    public const PATH_DASHBOARD = "civicrm/hprentals/dashboard";
    public const PATH_SETUP = "civicrm/hprentals/setup";
    public const PATH_RENTAL = "civicrm/hprentals/rental";
    public const PATH_RENTALS = "civicrm/hprentals/rentals";
    public const PATH_SERVICE = "civicrm/hprentals/service";
    public const PATH_SERVICES = "civicrm/hprentals/services";
    public const PATH_METHOD = "civicrm/hprentals/method";
    public const PATH_METHODS = "civicrm/hprentals/methods";
    public const PATH_INVOICE = "civicrm/hprentals/invoice";
    public const PATH_INVOICES = "civicrm/hprentals/invoices";
    public const PATH_PAYMENT = "civicrm/hprentals/payment";
    public const PATH_PAYMENTS = "civicrm/hprentals/payments";
    public const PATH_REPORTS = 'civicrm/hprentals/reports';
    public const SERVICE_FREQUENCY = [
        "once_off" => "Once Off",
        "every_month" => "Every Month",
        "less_than_6_m" => "Less than 6 months"
        ];


    //MENU

    public const MAIN_MENU = [
        'path' => '',
        'menu' => [
            'label' => 'Rentals',
            'icon' => 'crm-i fa-home',
            'name' => 'hprentals',
            'permission' => 'adminster CiviCRM'
        ]
    ];

    public const SETUP_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Configuration',
            'name' => 'hprentals_settings',
            'url' => self::PATH_SETUP,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const DASHBOARD_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Dashboard',
            'name' => 'hprentals_dashboard',
            'url' => self::PATH_DASHBOARD,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const TYPES_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Types',
            'name' => 'hprentals_types',
            'url' => self::PATH_SERVICES,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const METHODS_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Methods',
            'name' => 'hprentals_methods',
            'url' => self::PATH_METHODS,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const INVOICES_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Invoices',
            'name' => 'hprentals_invoices',
            'url' => self::PATH_INVOICES,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const REPORTS_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Reports',
            'name' => 'hprentals_reports',
            'url' => self::PATH_REPORTS,
            'permission' => 'adminster CiviCRM',
            'operator' => 'OR',
            //'separator' => 1,
            'is_active' => 1]];

    public const MENU = [
        self::MAIN_MENU,
        self::DASHBOARD_MENU,
        self::SETUP_MENU,
        self::TYPES_MENU,
        self::METHODS_MENU,
        self::INVOICES_MENU,
        self::REPORTS_MENU
    ];


    public static function getServiceFrequency(){
        return self::SERVICE_FREQUENCY;
    }
    /**
     * @param $input
     * @param $preffix_log
     */
    public static function writeLog($input, $preffix_log = "Hprentals Log")
    {
        try {
            if (self::getSaveLog()) {
                if (is_object($input)) {
                    $masquerade_input = (array)$input;
                } else {
                    $masquerade_input = $input;
                }
                if (is_array($masquerade_input)) {
                    $fields_to_hide = ['Signature'];
                    foreach ($fields_to_hide as $field_to_hide) {
                        unset($masquerade_input[$field_to_hide]);
                    }
                    Civi::log()->debug($preffix_log . "\n" . print_r($masquerade_input, TRUE));
                    return;
                }

                Civi::log()->debug($preffix_log . "\n" . $masquerade_input);
                return;
            }
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Dmszoho Configuration Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }

    /**
     * @return bool
     */
    public static function getSaveLog(): bool
    {
        $result = true;
        try {
            $result_ = self::getSettings(self::SAVE_LOG['slug']);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Write Log Config Required';
            self::showErrorMessage($error_message, $error_title);
        }
    }


    /**
     * @param string $error_message
     * @param string $error_title
     */
    public static function showErrorMessage(string $error_message, string $error_title): void
    {
        $session = CRM_Core_Session::singleton();
        $userContext = $session->readUserContext();
        CRM_Core_Session::setStatus($error_message, $error_title, 'error');
        CRM_Utils_System::redirect($userContext);
    }

    /**
     * @param string $error_message
     * @param string $error_title
     */
    public static function showStatusMessage(string $message, string $title): void
    {
        $session = CRM_Core_Session::singleton();
        $userContext = $session->readUserContext();
        CRM_Core_Session::setStatus($message, $title, 'warning', array('expires' => 2000));
//        CRM_Utils_System::redirect($userContext);
    }


    /**
     * @return mixed
     */
    public static function getSettings($setting = null)
    {
        $settings = CRM_Core_BAO_Setting::getItem(self::SETTINGS_NAME, self::SETTINGS_SLUG);
        if ($setting === null) {
            if (is_array($settings)) {
                return $settings;
            }
            $settings = [];
            return $settings;
        }
        if ($setting) {
            $return_setting = CRM_utils_array::value($setting, $settings);
            if (!$return_setting) {
                return false;
            }
            return $return_setting;
        }
    }


}



