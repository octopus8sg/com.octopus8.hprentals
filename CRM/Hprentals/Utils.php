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
    const TENANT_TABLE = 'civicrm_contact';
    const EXPENSE_TABLE = 'civicrm_o8_rental_expense';
    const INVOICE_TABLE = 'civicrm_o8_rental_invoice';
    const PAYMENT_TABLE = 'civicrm_o8_rental_payment';
    const METHOD_TABLE = 'civicrm_o8_rental_method';
    const RENTAL_TABLE = 'civicrm_o8_rental_rental';

    public const SAVE_LOG = [
        'slug' => 'save_log',
        'name' => 'Save Log',
        'description' => "Write debugging output to CiviCRM log file"];

    public const TEST_MODE = [
        'slug' => 'test_mode',
        'name' => 'Test Mode',
        'description' => "See all rentals (not only last month), Rentals menu, Creation of Invoices per Rentals"];
    public const DEFAULT_ENTITIES = [
        'RentalsExpense',
        'RentalsMethod',
        'RentalsRental',
        'RentalsInvoice',
        'RentalsPayment',
    ];
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
        "less_than_6_m" => "Less than 6 months",
        "more_than_6_m" => "More than 6 months"
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

    public const PAYMENTS_MENU = [
        'path' => self::MAIN_MENU['menu']['name'],
        'menu' => [
            'label' => 'Payments (test)',
            'name' => 'hprentals_payments',
            'url' => self::PATH_PAYMENTS,
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
            'label' => 'Rentals (Test)',
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
        self::SETUP_MENU,
        self::TYPES_MENU,
        self::METHODS_MENU,
        self::RENTALS_MENU,
        self::INVOICES_MENU,
        self::REPORTS_MENU,
        self::DASHBOARD_MENU
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
        'name' => "Rental Less Than 6 months",
        'frequency' => "less_than_6_m",
        'is_prorate' => 1,
        'is_refund' => 0,
        'amount' => 100,
    ];

    public const RENTAL_MORE_THAN_SIX_MONTH = [
        'name' => "Rental More Than 6 Months",
        'frequency' => "more_than_6_m",
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

    public static function getMenuItems()
    {
        $test_mode = self::getTestMode();
        $menu = [
            self::MAIN_MENU,
            self::DASHBOARD_MENU,
            self::TYPES_MENU,
            self::METHODS_MENU,
//            self::RENTALS_MENU,
            self::INVOICES_MENU,
            self::REPORTS_MENU,
            self::SETUP_MENU
        ];

        if ($test_mode == 1) {
            $menu = [
                self::MAIN_MENU,
                self::DASHBOARD_MENU,
                self::TYPES_MENU,
                self::METHODS_MENU,
                self::RENTALS_MENU,
                self::INVOICES_MENU,
                self::PAYMENTS_MENU,
                self::REPORTS_MENU,
                self::SETUP_MENU
            ];
        }
        return $menu;
    }

    public static function createDefaultExpenses()
    {
        $expenses = self::EXPENSES;
        foreach ($expenses as $expense) {
            try {
                $rentals_api = civicrm_api4('RentalsExpense', 'create', ['values' => $expense, 'checkPermissions' => FALSE]);
            } catch (Exception $e) {
                self::writeLog($e->getMessage());
            }
            try {
                if ($rentals_api['is_error']) {
                    // handle error
                    self::writeLog($rentals_api['error_message']);
                }
            } catch (Exception $e) {
                self::writeLog($e->getMessage());
            }
        }
    }

    public static function createDefaultMethods()
    {
        $methods = self::METHODS;
        foreach ($methods as $method) {
            try {
                $rentals_api =
                    civicrm_api4('RentalsMethod', 'create', ['values' => $method, 'checkPermissions' => FALSE],);
            } catch (Exception $e) {
                self::writeLog($e->getMessage());
            }
            try {
                if ($rentals_api['is_error']) {
                    // handle error
                    self::writeLog($rentals_api['error_message']);
                }
            } catch (Exception $e) {
                self::writeLog($e->getMessage());
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
                    $fields_to_hide = ['Signature', 'qfKey'];
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
        $result = false;
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
        usort($personRentals, function ($a, $b) {
            return strtotime($a['admission']) - strtotime($b['admission']);
        });
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
            $lastIndex = count($fakeDays) - 1;
            $i = 0;
            foreach ($fakeDays as $fakeDay) {

                $date_from = $fakeDay['admission'];
                $date_to = $fakeDay['discharge'];
                $existing_rent = self::getOverlappedRents($fak_id, $date_from, $date_to);
                // If an overlap is found, set a validation error message
                if ($existing_rent == 0) {
                    if ($i == $lastIndex) {
                        $rentals_api = civicrm_api3('RentalsRental', 'create', [
                            'tenant_id' => intval($fak_id),
                            'admission' => $date_from
                        ]);
                    } else {
                        $rentals_api = civicrm_api3('RentalsRental', 'create', [
                            'tenant_id' => intval($fak_id),
                            'admission' => $date_from,
                            'discharge' => $date_to
                        ]);
                    }
//                    self::writeLog($rentals_api, 'create Rentals Api');
                }
                if ($rentals_api['is_error']) {
                    // handle error
                    self::writeLog($rentals_api['error_message']);
                }
                $i++;
            }
        }
    }

    /**
     * @param $tenant_id
     * @param $date_from
     * @param $date_to
     * @return mixed
     */
    public static function getOverlappedRents($tenant_id, $date_from, $date_to, $rental_id = 0)
    {
        $rental_id = intval($rental_id);
        $my_rent_table = 'civicrm_o8_rental_rental';
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }
        // Retrieve the list of existing rents for the tenant
        if ($rental_id == 0) {
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
        }
        if ($rental_id != 0) {
            $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND ((admission <= %2 AND discharge >= %2)
                 OR (admission <= %3 AND discharge >= %3)
                 OR (admission >= %2 AND discharge <= %3))
            AND id != %4
    ", [
                1 => [$tenant_id, 'Integer'],
                2 => [$date_from, 'String'],
                3 => [$date_to, 'String'],
                4 => [$rental_id, 'Integer'],
            ]);
        }
        return $existing_rent;
    }

    /**
     * @param $tenant_id
     * @param $date_from
     * @param $date_to
     * @return mixed
     */
    public static function getEarlierRents($tenant_id, $date_from, $rental_id = 0)
    {
        $rental_id = intval($rental_id);
        $my_rent_table = 'civicrm_o8_rental_rental';
        // Retrieve the list of existing rents for the tenant
        if ($rental_id == 0) {
            $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND admission >= %2 
    ", [
                1 => [$tenant_id, 'Integer'],
                2 => [$date_from, 'String'],
            ]);
        }
        if ($rental_id != 0) {
            $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND admission >= %2 
            AND id != %3
    ", [
                1 => [$tenant_id, 'Integer'],
                2 => [$date_from, 'String'],
                3 => [$rental_id, 'Integer'],
            ]);
        }
        return $existing_rent;
    }

    /**
     * @param $tenant_id
     * @param $date_from
     * @param $date_to
     * @return mixed
     */
    public static function getUnfinishedRents($tenant_id, $rental_id = 0)
    {
        $rental_id = intval($rental_id);
        $my_rent_table = 'civicrm_o8_rental_rental';
        // Retrieve the list of existing rents for the tenant
        if ($rental_id == 0) {
            $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND discharge IS NULL
    ", [
                1 => [$tenant_id, 'Integer'],
            ]);
        }
        if ($rental_id != 0) {
            $existing_rent = CRM_Core_DAO::singleValueQuery("
        SELECT COUNT(*) AS overlap
        FROM {$my_rent_table}
        WHERE tenant_id = %1
            AND discharge IS NULL
            AND id != %2
    ", [
                1 => [$tenant_id, 'Integer'],
                2 => [$rental_id, 'Integer'],
            ]);
        }
        return $existing_rent;
    }

    /**
     * @param $op
     * @param $objectName
     * @param $params
     */
    public static function beforeSavingDo($op, $objectName, &$params): void
    {
        $defaultEntities = self::DEFAULT_ENTITIES;

        if (in_array($objectName, $defaultEntities)) {
//            self::writeLog($params, 'before save');
//            self::writeLog($op, 'before save op');
            $params = self::addCreatedByModifiedBy($op, $params);
            self::checkRentalOverlap($op, $objectName, $params);
            self::addRentalCode($op, $objectName, $params);
            self::addInvoiceCode($op, $objectName, $params);
            self::addPaymentCode($op, $objectName, $params);
//            self::writeLog($params, 'after save');

        }
    }

    /**
     * @param $op
     * @param $params
     * @return mixed
     */
    public static function addCreatedByModifiedBy($op, &$params)
    {
        if ($op == 'create' || $op == 'update' || $op == 'edit') {
            $userId = CRM_Core_Session::singleton()->getLoggedInContactID();
            $now = date('YmdHis');

            if ($op == 'create') {
                $params['created_id'] = $userId;
                $params['created_date'] = $now;
            }
            if ($op == 'update' || $op == 'edit') {
                $params['modified_id'] = $userId;
                $params['modified_date'] = $now;
            }

        }
        return $params;
    }

    /**
     * @param $op
     * @param $objectName
     * @param $params
     */
    public static function checkRentalOverlap($op, $objectName, &$params): void
    {
        if ($objectName == 'RentalsRental') {
//            self::writeLog($params, 'before overlap');
            if ($op == 'create' || $op == 'edit' || $op == 'update') {
                $date_from = $params['admission'];
                $date_to = $params['discharge'];
                $rental_id = $params['id'];
                $tenant_id = $params['tenant_id'];
                $existing_rent = self::getOverlappedRents($tenant_id, $date_from, $date_to, $rental_id);
                // If an overlap is found, set a validation error message
                if ($existing_rent > 0) {
                    self::showErrorMessage(ts('You already have a rent during this period.'), 'Date Overlap');
                    throw new CRM_Core_Exception(ts('You already have a rent during this period.'));
                }
            }
//            self::writeLog($params, 'after overlap');
        }
    }

    /**
     * @param $op
     * @param $objectName
     * @param $params
     */
    public static function addRentalCode($op, $objectName, &$params)
    {
        if ($objectName == 'RentalsRental') {
//            self::writeLog($params, 'before adding rental code');
            if ($op == 'create' || $op == 'edit' || $op == 'update') {
                $date_from = $params['admission'];
                $date_to = $params['discharge'];
                $tenant_id = $params['tenant_id'];
                // If an overlap is found, set a validation error message
                $code = self::generateRentalCode($tenant_id, $date_from, $date_to);
                $params['code'] = $code;
//                self::writeLog($params, 'after adding rental code');
            }
        }
    }

    /**
     * @param $op
     * @param $objectName
     * @param $params
     */
    public static function addInvoiceCode($op, $objectName, &$params)
    {
        if ($objectName == 'RentalsInvoice') {
//            self::writeLog($params, 'before adding invoice code');
            if ($op == 'create') {
//                self::writeLog($params, 'before adding invoice code 2');
                $invoiceNumber = self::generateInvoiceNumber();
//                self::writeLog($invoiceNumber, 'before adding invoice code 3');
                $params['code'] = $invoiceNumber;
//                self::writeLog($params, 'after adding invoice code');
            }
        }
    }

    /**
     * @param $op
     * @param $objectName
     * @param $params
     */
    public static function addPaymentCode($op, $objectName, &$params)
    {
        if ($objectName == 'RentalsPayment') {
//            self::writeLog($params, 'before adding invoice code');
            if ($op == 'create') {
//                self::writeLog($params, 'before adding invoice code 2');
                $invoiceNumber = self::generatePaymentNumber('RC');
//                self::writeLog($invoiceNumber, 'before adding invoice code 3');
                $params['code'] = $invoiceNumber;
//                self::writeLog($params, 'after adding invoice code');
            }
        }
    }

    public static function generateRentalCode($clientId, $startDate, $endDate)
    {
        // Convert the dates to the required format.
        $startDateFormatted = date('ymd', strtotime($startDate));
        $endDateFormatted = date('ymd', strtotime($endDate));

        // Pad the client ID with leading zeros up to 5 digits.
        $clientIdFormatted = str_pad($clientId, 5, '0', STR_PAD_LEFT);

        // Concatenate the formatted client ID and dates to generate the entity label.
        $entityLabel = 'c' . $clientIdFormatted . 'a' . $startDateFormatted . 'd' . $endDateFormatted;

        return $entityLabel;
    }

    public static function generateInvoiceNumber($prefix = 'HP')
    {
        // Get the current year and month
        $today = new DateTime();
        $monthYear = $today->format('ym');
        $invoiceParams = [
            'sequential' => 1,
            'prefix' => $prefix . $monthYear,
            'suffix' => '',
            'number' => 1,
        ];
        try {
            $lastInvoice = civicrm_api4('RentalsInvoice', 'get', [
                'select' => [
                    'code',
                ],
                'orderBy' => [
                    'code' => 'DESC',
                ],
                'limit' => 1,
                'checkPermissions' => FALSE,
                'where' => [
                    ['code', 'LIKE', $invoiceParams['prefix'] . '%'],
                ],
            ]);
//            self::writeLog($lastInvoice, 'lastInvoice');
        } catch (Exception $e) {
            self::writeLog($e->getMessage());
        }
        try {
            if (!empty($lastInvoice)) {
                $lastInvoiceNumber = $lastInvoice[0]['code'];
                $lastNumber = substr($lastInvoiceNumber, -4);
                $invoiceParams['number'] = (int)$lastNumber + 1;
//                self::writeLog($lastInvoiceNumber, 'lastInvoiceNumber');
            }
        } catch (Exception $e) {
            self::writeLog($e->getMessage());
        }
        $invoiceNumber = $invoiceParams['prefix'] . str_pad($invoiceParams['number'], 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }

    public static function generatePaymentNumber($prefix = 'RC')
    {
        // Get the current year and month
        $today = new DateTime();
        $monthYear = $today->format('ym');
        $paymentParams = [
            'sequential' => 1,
            'prefix' => $prefix . $monthYear,
            'suffix' => '',
            'number' => 1,
        ];
        try {
            $lastInvoice = civicrm_api4('RentalsPayment', 'get', [
                'select' => [
                    'code',
                ],
                'orderBy' => [
                    'code' => 'DESC',
                ],
                'limit' => 1,
                'checkPermissions' => FALSE,
                'where' => [
                    ['code', 'LIKE', $paymentParams['prefix'] . '%'],
                ],
            ]);
//            self::writeLog($lastInvoice, 'lastInvoice');
        } catch (Exception $e) {
            self::writeLog($e->getMessage());
        }
        try {
            if (!empty($lastInvoice)) {
                $lastInvoiceNumber = $lastInvoice[0]['code'];
                $lastNumber = substr($lastInvoiceNumber, -4);
                $paymentParams['number'] = (int)$lastNumber + 1;
//                self::writeLog($lastInvoiceNumber, 'lastPaymentNumber');
            }
        } catch (Exception $e) {
            self::writeLog($e->getMessage());
        }
        $invoiceNumber = $paymentParams['prefix'] . str_pad($paymentParams['number'], 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }

    // Prorate till the end of the month
    public static function prorateTillTheEndOfMonth($price, $date_start)
    {
        $start = new DateTime($date_start);
        $end = new DateTime('last day of this month');
        $days_in_month = $end->format('d');
        $days = $start->diff($end)->format('%a');
        $prorated_amount = round($price / $days_in_month * $days, 2);
        return $prorated_amount;
    }

// Prorate from the start of the month
    public static function prorateFromTheStartOfMonth($price, $date_end)
    {
        $start = new DateTime('first day of this month');
        $end = new DateTime($date_end);
        $days_in_month = $start->format('t');
        $days = $start->diff($end)->format('%a');
        $prorated_amount = round($price / $days_in_month * $days, 2);
        return $prorated_amount;
    }

// Prorate with start and end dates
    public static function prorateWithStartAndEnd($price, $date_start, $date_end)
    {
        $start = new DateTime($date_start);
        $end = new DateTime($date_end);
        $days = $start->diff($end)->format('%a');
        $prorated_amount = round($price / 30 * $days, 2);
        return $prorated_amount;
    }

    public static function calculate_expenses($invoice_start_date, $invoice_end_date, $rental_start_date)
    {
        $include_once_off = false;
        self::writeLog("$invoice_start_date ? $rental_start_date");
        if($rental_start_date == $invoice_start_date){
            $include_once_off = true;
            self::writeLog('$include_once_off is true');
        }
        if($include_once_off == false){
        $rentalsExpenses = \Civi\Api4\RentalsExpense::get(FALSE)
            ->addSelect('frequency:name', 'amount', 'name', 'is_prorate')
            ->addWhere('frequency:name', '<>', 'once_off')
            ->setLimit(0)
            ->execute();
        }
        if($include_once_off == true){
        $rentalsExpenses = \Civi\Api4\RentalsExpense::get(FALSE)
            ->addSelect('frequency:name', 'amount', 'name', 'is_prorate')
            ->setLimit(0)
            ->execute();
        }

        $description = "";
        $total = 0;
//        self::writeLog($rental_start_date, 'rental_start_date');
//        self::writeLog($start_date, 'start_date');
//        self::writeLog($end_date, 'end_date');
        $months_between = self::months_between($rental_start_date, $invoice_end_date);
//        self::writeLog($months_between, 'months_between');
        foreach ($rentalsExpenses as $rentalsExpense) {
            $frequency = $rentalsExpense['frequency:name'];
            $prorate = $rentalsExpense['is_prorate'];
            $expense_name = $rentalsExpense['name'];
            $monthly_price = $rentalsExpense['amount'];
            if ($prorate == 1) {
                $prorate_price = self::calculateProrate($invoice_start_date, $invoice_end_date, $monthly_price);
            } else {
                $prorate_price = $monthly_price;
            }
            if ($frequency === "once_off") {
                $description = $description . "\n" . $expense_name . " $" . $prorate_price;
                $total = $total + $prorate_price;
            }
            if ($months_between > 6) {
                if ($frequency === "more_than_6_m") {
                    $description = $description . "\n" . $expense_name . " $" . $prorate_price;
                    $total = $total + $prorate_price;
                }
            }
            if ($months_between <= 6) {
                if ($frequency === "less_than_6_m") {
                    $description = $description . "\n" . $expense_name . " $" . $prorate_price;
                    $total = $total + $prorate_price;
                }
            }
            if ($frequency === "every_month") {
                $description = $description . "\n" . $expense_name . " $" . $prorate_price;
                $total = $total + $prorate_price;
            }
        }
        $calculated_expence = ['description' => trim($description),
            'total' => $total];
        return $calculated_expence;
    }

    public static function calculateProrate($start_date_str, $end_date_str, $monthly_price)
    {
        $start_date = new DateTime($start_date_str);
        $end_date = new DateTime($end_date_str);

        // Calculate number of days between start and end date
        $num_days = $end_date->diff($start_date)->format('%a') + 1;

        // Calculate prorated amount
        $prorate_amount = round(($num_days / $start_date->format('t')) * $monthly_price, 2);

        return $prorate_amount;
    }

    public static function months_between($start_date_str, $end_date_str)
    {

        $start_date = DateTime::createFromFormat('Y-m-d', $start_date_str);
        $end_date = DateTime::createFromFormat('Y-m-d', $end_date_str);
//        self::writeLog($start_date);
//        self::writeLog($end_date);
        $interval = $start_date->diff($end_date);
        $months = $interval->y * 12 + $interval->m;
        return $months;
    }

    /**
     * Convert the array of rental_id-year-month into separate arrays for years and months.
     *
     * @param array $rent_and_months An array containing rental_id-year-month values.
     * @return array An array with 'years' and 'months' keys, each containing hierarchical arrays.
     * @throws Exception
     * @author Dr. Khindol Madraimov <khindol.madraimov@gmail.com>
     */
    public static function getHierSelectArrays($rent_and_months)
    {
        $years = [];
        $months = [];

        foreach ($rent_and_months as $month) {
            list($rental_id, $year, $month_number) = explode('-', $month);

            // Add the year to the $years array if it doesn't exist
            if (!isset($years[$year])) {
                $years[$year] = $year;
            }

            // Convert the month number to month name (e.g., 1 => "January", 2 => "February", etc.)
            $month_name = date("F", mktime(0, 0, 0, $month_number, 1));
            $year = intval($year);
            $month_number = intval($month_number);

            // Add the month to the $months array
            $months[$year][$month_number] = $month_name;
        }
        return ['years' => $years, 'months' => $months];
    }

    /**
     * Get an array of rental months for each rental record in the database.
     *
     * @return array An array containing rental_id-year-month values for all rental records.
     * @throws Exception
     * @author Dr. Khindol Madraimov <khindol.madraimov@gmail.com>
     */
    public static function getRentalMonths()
    {
        $rental_table = self::RENTAL_TABLE;
        $sql = "SELECT  
                id,
                admission,
                discharge 
        FROM $rental_table";
        $dao = CRM_Core_DAO::executeQuery($sql);
        $rental_months = [];
        $interval = \DateInterval::createFromDateString('1 month');

        while ($dao->fetch()) {
            $start = new \DateTime($dao->admission);
            if (!$dao->discharge) {
                $finished = false;
            }
            if ($dao->discharge) {
                $finished = true;
            }
            $end = new \DateTime($dao->discharge);
            $end->add($interval);
            $period = new \DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                $year = intval($dt->format('Y'));
                $month = intval($dt->format('m'));
                $is_current_month = self::isTheCurrentMonth($year, $month);
                if (!$is_current_month) {
                    $rental_months[] = $dao->id . '-' . $dt->format('Y-m');
                }
                if ($is_current_month) {
                    if ($finished) {
                        $rental_months[] = $dao->id . '-' . $dt->format('Y-m');
                    }
                }

            }

        }
        return $rental_months;
    }

    public static function isTheCurrentMonth($year, $month)
    {
        // Get the current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Convert the input year and month to integers
        $currentYear = intval($currentYear);
        $currentMonth = intval($currentMonth);
        $year = intval($year);
        $month = intval($month);

        // Compare the input year and month with the current year and month
        return ($year === $currentYear && $month === $currentMonth);
    }

    public static function getInvoiceTotalAmountByTenantId($tenant_id){
        $rentalsInvoice = null;
        $invoiceTotalAmountByTenantId = 0;
        $rentalsInvoices = \Civi\Api4\RentalsInvoice::get(FALSE)
            ->addSelect('SUM(amount)', 'rental_id.tenant_id')
            ->setLimit(25)
            ->addGroupBy('rental_id.tenant_id')
            ->setHaving([
                ['rental_id.tenant_id', '=', $tenant_id],
            ])
            ->execute();
        if($rentalsInvoices){
            $rentalsInvoice = $rentalsInvoices[0];
        }
        self::writeLog($rentalsInvoice);
        if($rentalsInvoice){
            $invoiceTotalAmountByTenantId = $rentalsInvoice["SUM:amount"];
        }
        return $invoiceTotalAmountByTenantId;
    }

    public static function getPaymentTotalAmountByTenantId($tenant_id){
        $rentalsPayment = null;
        $paymentTotalAmountByTenantId = 0;
        $rentalsPayments = \Civi\Api4\RentalsPayment::get(FALSE)
            ->addSelect('tenant_id', 'SUM(amount)')
            ->setLimit(25)
            ->addGroupBy('tenant_id')
            ->setHaving([
                ['tenant_id', '=', $tenant_id],
            ])
            ->execute();;
        if($rentalsPayments){
            $rentalsPayment = $rentalsPayments[0];
        }
        self::writeLog($rentalsPayment);
        if($rentalsPayment){
            $paymentTotalAmountByTenantId = $rentalsPayment["SUM:amount"];
        }
        return $paymentTotalAmountByTenantId;
    }

}





