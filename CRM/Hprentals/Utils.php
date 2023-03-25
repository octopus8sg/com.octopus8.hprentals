<?php

use CRM_Hprentals_ExtensionUtil as E;

//require_once 'civicrm.config.php';
//require_once 'CRM/Core/Config.php';
require_once 'vendor/autoload.php'; // assumes that Faker is installed using composer
use Faker\Factory;

class CRM_Hprentals_Utils
{

    //SETTINGS

    public const SETTINGS_NAME = "Hprentals Settings";
    public const SETTINGS_SLUG = 'hprentals_settings';
    public const FAKER_GROUP = 'HpRentals Faker Group';
    public const FAKER_COUNT = 10;

    public const SAVE_LOG = [
        'slug' => 'save_log',
        'name' => 'Save Log',
        'description' => "Write debugging output to CiviCRM log file"];

    public const TEST_MODE = [
        'slug' => 'test_mode',
        'name' => 'Test Mode',
        'description' => "See all rentals (not only last month), Rentals menu, Creation of Invoices per Rentals"];

    //PATHS
    public const PATH_DASHBOARD = "civicrm/rentals/dashboard";
    public const PATH_SETUP = "civicrm/rentals/setup";
    public const PATH_RENTAL = "civicrm/rentals/rental";
    public const PATH_RENTALS = "civicrm/rentals/search";
    public const PATH_EXPENSE = "civicrm/rentals/expense";
    public const PATH_EXPENSES = "civicrm/rentals/expenses";
    public const PATH_METHOD = "civicrm/rentals/method";
    public const PATH_METHODS = "civicrm/rentals/methods";
    public const PATH_INVOICE = "civicrm/rentals/invoice";
    public const PATH_INVOICES = "civicrm/rentals/invoices";
    public const PATH_PAYMENT = "civicrm/rentals/payment";
    public const PATH_PAYMENTS = "civicrm/rentals/payments";
    public const PATH_REPORTS = 'civicrm/rentals/reports';
    public const EXPENSE_FREQUENCY = [
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
            'url' => self::PATH_EXPENSES,
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

    public const RENTALS_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Rentals',
            'name' => 'hprentals_rentals',
            'url' => self::PATH_RENTALS,
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
        self::RENTALS_MENU,
        self::SETUP_MENU,
        self::TYPES_MENU,
        self::METHODS_MENU,
        self::INVOICES_MENU,
        self::REPORTS_MENU
    ];


    //EXPENSES

    public const ADMINISTRATIVE_FEE = [
        'name' => "Administrative Fee",
        'frequency' => "once_off",
        'is_prorate' => 0,
        'is_refund' => 0,
        'amount' => 50,
    ];

    public const RENTAL_DEPOSIT = [
        'name' => "Rental Deposit",
        'frequency' => "once_off",
        'is_prorate' => 0,
        'is_refund' => 1,
        'amount' => 100,
    ];

    public const LOUNDRY = [
        'name' => "Laundry",
        'frequency' => "every_month",
        'is_prorate' => 1,
        'is_refund' => 0,
        'amount' => 40,
    ];

    public const RENTAL_LESS_THAN_SIX_MONTH = [
        'name' => "Rental Less Than 6 month",
        'frequency' => "less_than_6_m",
        'is_prorate' => 1,
        'is_refund' => 0,
        'amount' => 100,
    ];

    public const RENTAL_MORE_THAN_SIX_MONTH = [
        'name' => "Rental More Than 6 Month",
        'frequency' => "every_month",
        'is_prorate' => 1,
        'is_refund' => 0,
        'amount' => 250,
    ];

    public const INVOICE_WAIVER = [
        'name' => "Invoice Waiver",
        'frequency' => "once_off",
        'is_prorate' => 0,
        'is_refund' => 0,
        'amount' => -150,
    ];

    public const EXPENSES = [
        self::ADMINISTRATIVE_FEE,
        self::RENTAL_DEPOSIT,
        self::LOUNDRY,
        self::RENTAL_LESS_THAN_SIX_MONTH,
        self::RENTAL_MORE_THAN_SIX_MONTH,
        self::INVOICE_WAIVER
    ];

    //METHODS

    public const CASH = [
        'name' => "Cash",
    ];

    public const VISA = [
        'name' => "VISA",
    ];

    public const MASTER = [
        'name' => "MASTER",
    ];

    public const NETS = [
        'name' => "NETS",
    ];

    public const PAYPAL = [
        'name' => "PayPal",
    ];

    public const OTHER = [
        'name' => "Other",
    ];

    public const METHODS = [
        self::CASH,
        self::VISA,
        self::MASTER,
        self::NETS,
        self::PAYPAL,
        self::OTHER
    ];

    public static function getExpenseFrequency()
    {
        return self::EXPENSE_FREQUENCY;
    }

    public static function createDefaultExpenses()
    {
        $expenses = self::EXPENSES;
        foreach ($expenses as $expense) {
            $rentals_api = civicrm_api3('RentalsExpense', 'create', $expense);
            if ($rentals_api['is_error']) {
                // handle error
                self::writeLog($rentals_api['error_message']);
            }
        }
    }

    public static function createDefaultMethods()
    {
        $methods = self::METHODS;
        foreach ($methods as $method) {
            $rentals_api = civicrm_api3('RentalsMethod', 'create', $method);
            if ($rentals_api['is_error']) {
                // handle error
                self::writeLog($rentals_api['error_message']);
            }
        }
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
     * @return bool
     */
    public static function getTestMode(): bool
    {
        $result = true;
        try {
            $result_ = self::getSettings(self::TEST_MODE['slug']);
            if ($result_ == 1) {
                $result = true;
            }
            return $result;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            $error_title = 'Test Mode Config Required';
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

    /**
     * @param $id
     * @return mixed|null
     */
    public static function getMyEntity($id, $entityName)
    {
        $myentity = null;
        $entities = civicrm_api4($entityName, 'get', ['where' => [['id', '=', $id]], 'limit' => 1]);
        if (!empty($entities)) {
            $myentity = $entities[0];
        }

        return $myentity;
    }

    public static function create_fake_individuals()
    {
        $faker = Faker\Factory::create();
        $contact_type = 'Individual';
//        $api = civicrm_api3('Contact', 'create');

        // check if the group already exists
        $num_individuals = self::FAKER_COUNT;
        $group_title = self::FAKER_GROUP;
        $faker_contacts = self::get_faker_contacts();
        $faker_contacts_count = sizeof($faker_contacts);
        $num_individuals = $num_individuals - $faker_contacts_count;


        if ($num_individuals <= 0) {
            self::writeLog("There is already $faker_contacts_count fake Individual entities in the '$group_title' group.");
        }

        $group_params = array('title' => $group_title);
        $group_api = civicrm_api3('Group', 'get', $group_params);
        if ($group_api['is_error']) {
            // handle error
            self::writeLog($group_api['error_message']);
        }
        if (count($group_api['values']) > 0) {
            // group already exists, use existing group ID
            $group = reset($group_api['values']);
            $group_id = $group['id'];
        } else {
            // group doesn't exist, create a new group
            $group_api = civicrm_api3('Group', 'create', $group_params);
            if ($group_api['is_error']) {
                // handle error
                self::writeLog($group_api['error_message']);
            }
            $group_id = $group_api['id'];
        }

        for ($i = 1; $i <= $num_individuals; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = $faker->safeEmail;
            $phone = $faker->phoneNumber;
            $streetAddress = $faker->streetAddress;
            $city = $faker->city;
            $postalCode = $faker->postcode;
            $params = array(
                'contact_type' => $contact_type,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
            );
//            $result = $api->create($params);
            $result = civicrm_api3('Contact', 'create', $params);
            if ($result['is_error']) {
                // handle error
                self::writeLog($result['error_message']);
            }

            // add contact to the group
            $contact_id = $result['id'];
            $group_contact_api = civicrm_api3('GroupContact', 'create', array(
                'group_id' => $group_id,
                'contact_id' => $contact_id,
            ));
            if ($group_contact_api['is_error']) {
                // handle error
                self::writeLog($group_contact_api['error_message']);
            }

            $phone_api = civicrm_api3('Phone', 'create', [
                'contact_id' => $contact_id,
                'phone' => $phone,
                'phone_type_id' => 1, // Set phone type as "Home"
            ]);

            if ($phone_api['is_error']) {
                // handle error
                self::writeLog($phone_api['error_message']);
            }
            $address_api = civicrm_api3('Address', 'create', [
                'contact_id' => $contact_id,
                'street_address' => $streetAddress,
                'city' => $city,
                'postal_code' => $postalCode,
                'location_type_id' => 1, // Set address type as "Home"
            ]);

            if ($address_api['is_error']) {
                // handle error
                self::writeLog($phone_api['error_message']);
            }
        }
        // return success message
        return "Created $num_individuals fake Individual entities in CiviCRM and added them to the '$group_title' group.";
    }

    public static function get_faker_contacts()
    {
        $group_title = self::FAKER_GROUP;
        $group_params = array('title' => $group_title);
        $group_api = civicrm_api3('Group', 'get', $group_params);
        if ($group_api['is_error']) {
            // handle error
            self::writeLog($group_api['error_message']);
            return array();
        }
        if (count($group_api['values']) == 0) {
            // group doesn't exist, return empty array
            return array();
        }
        $group = reset($group_api['values']);
        $group_id = $group['id'];
        $group_contact_api = civicrm_api3('GroupContact', 'get', array(
            'group_id' => $group_id,
            'options' => array('limit' => 0),
        ));
        if ($group_contact_api['is_error']) {
            // handle error
            self::writeLog($group_contact_api['error_message']);
            return array();
        }
        $contacts = array();
        foreach ($group_contact_api['values'] as $group_contact) {
            $contact_id = $group_contact['contact_id'];
            $contact_api = civicrm_api3('Contact', 'getsingle', array(
                'id' => $contact_id,
            ));
            if ($contact_api['is_error']) {
                // handle error
                self::writeLog($contact_api['error_message']);
            } else {
                $contacts[] = $contact_api;
            }
        }
        return $contacts;
    }

    public static function delete_faker_contacts()
    {
        $group_title = self::FAKER_GROUP;
        $group_params = array('title' => $group_title);
        $group_api = civicrm_api3('Group', 'get', $group_params);
        if ($group_api['is_error']) {
            // handle error
            return $group_api['error_message'];
        }
        if (count($group_api['values']) == 0) {
            // group doesn't exist, nothing to delete
            return;
        }
        $group = reset($group_api['values']);
        $group_id = $group['id'];
        $group_contact_api = civicrm_api3('GroupContact', 'get', array(
            'group_id' => $group_id,
            'options' => array('limit' => 0),
        ));
        if ($group_contact_api['is_error']) {
            // handle error
            self::writeLog($group_contact_api['error_message']);
        }
        foreach ($group_contact_api['values'] as $group_contact) {
            $contact_id = $group_contact['contact_id'];
            $delete_params = array('id' => $contact_id);
            $delete_api = civicrm_api3('Contact', 'delete', $delete_params);
            if ($delete_api['is_error']) {
                // handle error
                self::writeLog($delete_api['error_message']);
            }
        }
        $delete_group_api = civicrm_api3('Group', 'delete', array('id' => $group_id));
        if ($delete_group_api['is_error']) {
            // handle error
            self::writeLog($delete_group_api['error_message']);
        }
    }

    public static function createFakerDateSets($startDate, $endDate)
    {
        $faker = Faker\Factory::create();

// Define the number of rental sets per person
        $rentalSets = 5;

// Define an empty array to store the rental sets
        $personRentals = [];

        // Loop through each rental set for this person
        for ($j = 1; $j <= $rentalSets; $j++) {
            // Generate a random start date within the range

            $nextStartDate = date('Y-m-d', strtotime("$startDate +60 day"));
            if ($nextStartDate > $endDate) {
                break;
            }
            do {
                // Generate a random start date within the range, after the person's start date
                $startDateObj = $faker->dateTimeBetween($startDate, $nextStartDate);

                // Generate a random end date within the range, after the start date
                $endDateObj = $faker->dateTimeBetween($startDateObj, $endDate);

                // Calculate the duration of the rental period in months
                $diff = $startDateObj->diff($endDateObj);
                $months = ($diff->y * 12) + $diff->m;

                // Check if the duration of the rental period is less than or equal to 7 months
                if ($months <= 7) {
                    break;
                }
            } while (true);

            // Format the dates as strings
            $startDateStr = $startDateObj->format('Y-m-d');
            $endDateStr = $endDateObj->format('Y-m-d');

            // Add the rental set to the array
            $personRentals[] = ['admission' => $startDateStr, 'discharge' => $endDateStr];

            // Exclude this date range from future rentals for this person
            $startDate = date('Y-m-d', strtotime($endDateStr . " +1 day"));
        }

        // Add the person's rental sets to the rentals array

// Output the rentals array
        return $personRentals;
    }

    public static function create_faker_rentals()
    {
        $fakers = self::get_faker_contacts();
        foreach ($fakers as $faker) {
            $lastDay = date('Y-m-t');;
            $twoYearsAgo = new DateTime('-2 years');
            $firstDay = $twoYearsAgo->format('Y-m-t');
            $fak_id = intval($faker['contact_id']);
//            echo $fak_id;
            $fakeDays = self::createFakerDateSets($firstDay, $lastDay);
            foreach ($fakeDays as $fakeDay) {
                $date_from = $fakeDay['admission'];
                $date_to = $fakeDay['discharge'];
                $existing_rent = self::getOverlappedRents($fak_id, $date_from, $date_to);
                // If an overlap is found, set a validation error message
                if ($existing_rent == 0) {
                    $rentals_api = civicrm_api3('RentalsRental', 'create', [
                        'tenant_id' => intval($fak_id),
                        'admission' => $date_from,
                        'discharge' => $date_to,
                    ]);
                    self::writeLog($rentals_api);
                }
                if ($rentals_api['is_error']) {
                    // handle error
                    self::writeLog($rentals_api['error_message']);
                }
            }
        }
    }

    /**
     * @param $tenant_id
     * @param $date_from
     * @param $date_to
     * @return mixed
     */
    public static function getOverlappedRents($tenant_id, $date_from, $date_to)
    {

        $my_rent_table = 'civicrm_o8_rental_rental';
        // Retrieve the list of existing rents for the tenant
        $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND ((admission <= %2 AND discharge >= %2)
                 OR (admission <= %3 AND discharge >= %3)
                 OR (admission >= %2 AND discharge <= %3))
    ", [
            1 => [$tenant_id, 'Integer'],
            2 => [$date_from, 'String'],
            3 => [$date_to, 'String'],
        ]);
        return $existing_rent;
    }
}



