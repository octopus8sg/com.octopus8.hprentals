<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

use CRM_Hprentals_Utils as U;

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */
class CRM_Hprentals_Form_Report_RentalTenantSummary extends CRM_Report_Form
{

    protected $_charts = [
    ];

    protected $_from_rentals = "";

    protected $_from_invoice = "";

    protected $_from_payment = "";

    protected $_created_date_end_date = null;

    protected $_created_date_start_date = null;

    protected $_customGroupExtends = [
    ];
    /**
     * To what frequency group-by a date column
     *
     * @var array
     */
    protected $_groupByDateFreq = [
        'MONTH' => 'Month',
        'YEARWEEK' => 'Week',
        'DATE' => 'Day',
        'QUARTER' => 'Quarter',
        'YEAR' => 'Year',
        'FISCALYEAR' => 'Fiscal Year',
    ];

    /**
     * This report has been optimised for group filtering.
     *
     * @var bool
     * @see https://issues.civicrm.org/jira/browse/CRM-19170
     */
    protected $groupFilterNotOptimised = FALSE;

    /**
     * Use the generic (but flawed) handling to implement full group by.
     *
     * Note that because we are calling the parent group by function we set this to FALSE.
     * The parent group by function adds things to the group by in order to make the mysql pass
     * but can create incorrect results in the process.
     *
     * @var bool
     */
    public $optimisedForOnlyFullGroupBy = FALSE;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->_columns = [
            'civicrm_contact' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => array_merge(
                    $this->getBasicContactFields(),
                    [
                        'sort_name' => [
                            'title' => ts('Contact Name'),
                            'no_repeat' => TRUE,
                            'default' => TRUE,
                            'required' => TRUE,
                        ],
                    ]
                ),
                'filters' => $this->getBasicContactFilters(['deceased' => NULL]),
                'grouping' => 'contact-fields',
                'group_bys' => [
                    'id' => [
                        'title' => ts('Contact ID'),
                        'default' => TRUE],
//                    'sort_name' => [
//                        'title' => ts('Contact Name'),
//                    ],
                ],
            ],
            'civicrm_o8_rental_rental' => [
                'dao' => 'CRM_Hprentals_DAO_RentalsRental',
                //'bao'           => 'CRM_Hprentals_BAO_RentalsRental',
                'fields' => [
                    'id' => [
                        'title' => ts('Rental Amount Stats'),
                        'default' => TRUE,
                        'statistics' => [
                            'count' => ts('Rentals'),
                        ],
                    ],
                ],
                'grouping' => 'rental-fields',
                'filters' => [
                    'admission' => [
                        'title' => ts('Rental Admission'),
                        'operatorType' => CRM_Report_Form::OP_DATE,
                        'type' => CRM_Utils_Type::T_DATE,
                    ],
                    'discharge' => [
                        'title' => ts('Rental Discharge'),
                        'operatorType' => CRM_Report_Form::OP_DATE,
                        'type' => CRM_Utils_Type::T_DATE,
                    ],
                    'payment_created_date' => [
                        'name' => 'created_date',
                        'title' => ts('Payment & Invoice Date'),
                        'operatorType' => CRM_Report_Form::OP_DATE,
                        'type' => CRM_Utils_Type::T_DATE,
                    ]
                ],
            ],
            'civicrm_o8_rental_payment' => [
                'dao' => 'CRM_Hprentals_DAO_RentalsPayment',
                'fields' => [
                    'payment_amount' => [
                        'name' => 'amount',
                        'title' => ts('Payment Stats'),
                        'default' => TRUE,
                        'statistics' => [
                            'count' => ts('Payments Count'),
                            'sum' => ts('Payment Sum'),
                            'avg' => ts('Payment Avg'),
                        ],
                    ],
                ],
                'grouping' => 'rental-fields',
            ],
            'civicrm_o8_rental_invoice' => [
                'dao' => 'CRM_Hprentals_DAO_RentalsPayment',
                'fields' => [
                    'invoice_amount' => [
                        'name' => 'amount',
                        'title' => ts('Invoice Stats'),
                        'default' => TRUE,
                        'statistics' => [
                            'count' => ts('Invoices Count'),
                            'sum' => ts('Invoices Sum'),
                            'avg' => ts('Invoices Avg'),
                            'balance' => ts('Balance')
                        ],
                    ],
                ],
                'grouping' => 'rental-fields',
            ],

        ];


        parent::__construct();
    }

    /**
     * Set select clause.
     */
    public function select()
    {
        $select = [];
        $this->_columnHeaders = [];

        foreach ($this->_columns as $tableName => $table) {
            if (array_key_exists('group_bys', $table)) {
                foreach ($table['group_bys'] as $fieldName => $field) {
                    if (!empty($this->_params['group_bys'][$fieldName])) {
                        switch (CRM_Utils_Array::value($fieldName, $this->_params['group_bys_freq'])) {
                            case 'YEARWEEK':
                                $select[] = "DATE_SUB({$field['dbAlias']}, INTERVAL WEEKDAY({$field['dbAlias']}) DAY) AS {$tableName}_{$fieldName}_start";
                                $select[] = "YEARWEEK({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "WEEKOFYEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Week Beginning');
                                break;

                            case 'YEAR':
                                $select[] = "MAKEDATE(YEAR({$field['dbAlias']}), 1)  AS {$tableName}_{$fieldName}_start";
                                $select[] = "YEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "YEAR({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Year Beginning');
                                break;

                            case 'FISCALYEAR':
                                $config = CRM_Core_Config::singleton();
                                $fy = $config->fiscalYearStart;
                                $fiscal = self::fiscalYearOffset($field['dbAlias']);

                                $select[] = "DATE_ADD(MAKEDATE({$fiscal}, 1), INTERVAL ({$fy['M']})-1 MONTH) AS {$tableName}_{$fieldName}_start";
                                $select[] = "{$fiscal} AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "{$fiscal} AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Fiscal Year Beginning');
                                break;

                            case 'MONTH':
                                $select[] = "DATE_SUB({$field['dbAlias']}, INTERVAL (DAYOFMONTH({$field['dbAlias']})-1) DAY) as {$tableName}_{$fieldName}_start";
                                $select[] = "MONTH({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "MONTHNAME({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = ts('Month Beginning');
                                break;

                            case 'DATE':
                                $select[] = "DATE({$field['dbAlias']}) as {$tableName}_{$fieldName}_start";
                                $field['title'] = ts('Date');
                                break;

                            case 'QUARTER':
                                $select[] = "STR_TO_DATE(CONCAT( 3 * QUARTER( {$field['dbAlias']} ) -2 , '/', '1', '/', YEAR( {$field['dbAlias']} ) ), '%m/%d/%Y') AS {$tableName}_{$fieldName}_start";
                                $select[] = "QUARTER({$field['dbAlias']}) AS {$tableName}_{$fieldName}_subtotal";
                                $select[] = "QUARTER({$field['dbAlias']}) AS {$tableName}_{$fieldName}_interval";
                                $field['title'] = 'Quarter';
                                break;
                        }
                        if (!empty($this->_params['group_bys_freq'][$fieldName])) {
                            $this->_interval = $this->_params['group_bys_freq'][$fieldName];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['title'] = $field['title'];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['type'] = $field['type'];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_start"]['group_by'] = $this->_params['group_bys_freq'][$fieldName];

                            // just to make sure these values are transferred to rows.
                            // since we need that for calculation purpose,
                            // e.g making subtotals look nicer or graphs
                            $this->_columnHeaders["{$tableName}_{$fieldName}_interval"] = ['no_display' => TRUE];
                            $this->_columnHeaders["{$tableName}_{$fieldName}_subtotal"] = ['no_display' => TRUE];
                        }
                    }
                }
            }

            if (array_key_exists('fields', $table)) {
                foreach ($table['fields'] as $fieldName => $field) {
                    if (!empty($field['required']) ||
                        !empty($this->_params['fields'][$fieldName])
                    ) {
                        // only include statistics columns if set
                        if (!empty($field['statistics'])) {
                            foreach ($field['statistics'] as $stat => $label) {
                                $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['title'] = $label;
                                $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = $field['type'];
                                $this->_statFields[] = "{$tableName}_{$fieldName}_{$stat}";
                                if ($tableName == 'civicrm_o8_rental_rental') {
                                    switch (strtolower($stat)) {
                                        case 'sum':
                                            $select[] = "SUM({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                                            break;
                                        case 'count':
                                            $select[] = "COUNT({$field['dbAlias']}) as {$tableName}_{$fieldName}_{$stat}";
                                            break;
                                        case 'avg':
                                            $select[] = "ROUND(AVG({$field['dbAlias']}),2) as {$tableName}_{$fieldName}_{$stat}";
                                            break;
                                    }
                                }
                                switch (strtolower($stat)) {
                                    case 'count':
                                        $this->_columnHeaders["{$tableName}_{$fieldName}_{$stat}"]['type'] = CRM_Utils_Type::T_INT;
                                        break;
                                }
                        }
                    } else {
                        $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = $field['type'] ?? NULL;
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'] ?? NULL;
                    }
                }
            }
        }
    }



$this->_selectClauses = $select;
$this->_select = "SELECT " . implode(', ', $select) . " ";
    }

/**
 * Set form rules.
 *
 * @param array $fields
 * @param array $files
 * @param CRM_Report_Form_Contribute_Summary $self
 *
 * @return array
 */
public
static function formRule($fields, $files, $self)
{
    // Check for searching combination of display columns and
    // grouping criteria
    $ignoreFields = ['amount', 'sort_name'];
    $errors = $self->customDataFormRule($fields, $ignoreFields);

    if (empty($fields['fields']['amount'])) {
        foreach ([
                     'count_value',
                     'sum_value',
                     'avg_value',
                 ] as $val) {
            if (!empty($fields[$val])) {
                $errors[$val] = ts("Please select the Amount Statistics");
            }
        }
    }

    return $errors;
}

/**
 * Set from clause.
 *
 * @param string $entity
 *
 * @todo fix function signature to match parent. Remove hacky passing of $entity
 * to acheive unclear results.
 */
public
function from($entity = NULL)
{

    $this->setFromBase('civicrm_contact');
    $from = $this->_from;
    $this->_from_rentals = $from . "
             INNER JOIN civicrm_o8_rental_rental {$this->_aliases['civicrm_o8_rental_rental']}
                     ON {$this->_aliases['civicrm_contact']}.id 
                     = {$this->_aliases['civicrm_o8_rental_rental']}.tenant_id ";

    $this->_from_payment = $from . "
             INNER JOIN civicrm_o8_rental_payment {$this->_aliases['civicrm_o8_rental_payment']}
                     ON {$this->_aliases['civicrm_contact']}.id = 
                     {$this->_aliases['civicrm_o8_rental_payment']}.tenant_id ";

    $this->_from_invoice = $from . "
             INNER JOIN civicrm_o8_rental_rental {$this->_aliases['civicrm_o8_rental_rental']}
                     ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_o8_rental_rental']}.tenant_id 
             INNER JOIN civicrm_o8_rental_invoice   {$this->_aliases['civicrm_o8_rental_invoice']}
                     ON {$this->_aliases['civicrm_o8_rental_rental']}.id = {$this->_aliases['civicrm_o8_rental_invoice']}.rental_id             ";

    $this->_from = $this->_from_rentals;
    //for contribution batches

}

/**
 * Set group by clause.
 */
public
function groupBy()
{
    parent::groupBy();

    $isGroupByFrequency = !empty($this->_params['group_bys_freq']);

    if (!empty($this->_params['group_bys']) &&
        is_array($this->_params['group_bys'])
    ) {

        if (!empty($this->_statFields) &&
            (($isGroupByFrequency && count($this->_groupByArray) <= 1) || (!$isGroupByFrequency)) &&
            !$this->_having
        ) {
            $this->_rollup = " WITH ROLLUP";
        }
        $groupBy = [];
        foreach ($this->_groupByArray as $key => $val) {
            if (strpos($val, ';;') !== FALSE) {
                $groupBy = array_merge($groupBy, explode(';;', $val));
            } else {
                $groupBy[] = $this->_groupByArray[$key];
            }
        }
        $this->_groupBy = "GROUP BY " . implode(', ', $groupBy);
    } else {
        $this->_groupBy = "GROUP BY {$this->_aliases['civicrm_contact']}.id";
    }
    $this->_groupBy .= $this->_rollup;
}

    public function storeWhereHavingClauseArray() {
        parent::storeWhereHavingClauseArray();
        $whereClauses = $this->_whereClauses;
        $admission_start_date = $admission_end_date = $discharge_start_date =  $discharge_end_date = null;

        foreach ($whereClauses as $key => $element) {
//            U::writeLog($element, $key);
//            // Check for 'o8_rental_rental_civireport.admission >='
//            if (preg_match('/admission >= (\d{14})/', $element, $matches)) {
//                $admission_start_date = $matches[1];
//                U::writeLog($matches);
//            }
//
//            // Check for 'o8_rental_rental_civireport.admission <='
//            if (preg_match('/admission <= (\d{14})/', $element, $matches)) {
//                $admission_end_date = $matches[1];
//                U::writeLog($matches);
//            }
//
//            if (preg_match('/discharge >= (\d{14})/', $element, $matches)) {
//                $discharge_start_date = $matches[1];
//                U::writeLog($matches);
//            }
//
//            // Check for 'o8_rental_rental_civireport.discharge <='
//            if (preg_match('/discharge <= (\d{14})/', $element, $matches)) {
//                $discharge_end_date = $matches[1];
//                U::writeLog($matches);
//            }
//
            if (preg_match('/created_date >= (\d{14})/', $element, $matches)) {
                $this->_created_date_start_date = $matches[1];
//                U::writeLog($matches);
                unset($whereClauses[$key]);
            }
            if (preg_match('/created_date <= (\d{14})/', $element, $matches)) {
                $this->_created_date_end_date = $matches[1];
//                U::writeLog($matches);
                unset($whereClauses[$key]);
            }
        }

// Check if both dates were found
//        if ($admission_start_date !== null && $admission_end_date !== null) {
//            U::writeLog("Admission Start Date: $admission_start_date\n");
//            U::writeLog("Admission End Date: $admission_end_date\n");
//        } else {
//            U::writeLog("Dates not found in the array.\n");
//        }
//
//        $this->_whereClauses[] = "{$this->_aliases['civicrm_membership']}.is_test = 0 AND
//                              {$this->_aliases['civicrm_contact']}.is_deleted = 0";
        $this->_whereClauses = $whereClauses;
    }

/**
 * Set statistics.
 *
 * @param array $rows
 *
 * @return array
 *
 * @throws \CRM_Core_Exception
 */
public
function statistics(&$rows)
{
    $statistics = parent::statistics($rows);

    $group = ' GROUP BY ' . implode(', ', $this->_groupByArray);

    $this->from();
    $this->customDataFrom();

    // Ensure that Extensions that modify the from statement in the sql also modify it in the statistics.
    CRM_Utils_Hook::alterReportVar('sql', $this, $this);

    $contriQuery = "
        COUNT({$this->_aliases['civicrm_o8_rental_rental']}.id ) as civicrm_o8_rental_rental_id_count 
              {$this->_from} {$this->_where}
        ";

    $contriSQL = "SELECT {$contriQuery} {$group} {$this->_having}";
    $contriDAO = CRM_Core_DAO::executeQuery($contriSQL);
    $this->addToDeveloperTab($contriSQL);

    $rentalCount = $tenantCount = 0;
    while ($contriDAO->fetch()) {
        $tenantCount += 1;
        $rentalCount += $contriDAO->civicrm_o8_rental_rental_id_count;

    }

//        foreach ($currencies as $currency) {
//            $totalAmount[] = CRM_Utils_Money::format($currAmount[$currency], $currency) .
//                " (" . $currCount[$currency] . ")";
//            $average[] = CRM_Utils_Money::format(($currAverage[$currency] / $averageCount[$currency]), $currency);
//        }


    if (1) {
        $statistics['counts']['tenants_count'] = [
            'title' => ts('Total Tenants'),
            'value' => $tenantCount,
            'type' => CRM_Utils_Type::T_INT,
        ];
        $statistics['counts']['rentals_count'] = [
            'title' => ts('Total Rentals'),
            'value' => $rentalCount,
            'type' => CRM_Utils_Type::T_INT,
        ];
    }
    return $statistics;
}

/**
 * Build chart.
 *
 * @param array $original_rows
 */


/**
 * Alter display of rows.
 *
 * Iterate through the rows retrieved via SQL and make changes for display purposes,
 * such as rendering contacts as links.
 *
 * @param array $rows
 *   Rows generated by SQL, with an array for each row.
 */
public
function alterDisplay(&$rows)
{

    $entryFound = FALSE;
    $total_invoice_amount_sum =
    $total_invoice_amount_count =
    $total_invoice_amount_avg =
    $total_payment_amount_sum =
    $total_payment_amount_count =
    $total_payment_amount_avg = 0;
    $created_date_end_date = $this->_created_date_end_date;
    $created_date_start_date = $this->_created_date_start_date;


    foreach ($rows as $rowNum => $row) {
        if (isset($row['civicrm_contact_sort_name']) &&
            isset($row['civicrm_contact_id'])
        ) {
            $tenant_id = intval($row['civicrm_contact_id']);
            $paymentWhere = [
                ['tenant_id', '=', $tenant_id],
            ];
            $invoiceWhere = [
                ['rental_id.tenant_id', '=', $tenant_id],
            ];
            if($created_date_start_date){
                $paymentWhere[] = ['created_date', '>=', $created_date_start_date];
                $invoiceWhere[] = ['created_date', '>=', $created_date_start_date];
            }
            if($created_date_end_date){
                $paymentWhere[] = ['created_date', '<=', $created_date_end_date];
                $invoiceWhere[] = ['created_date', '<=', $created_date_end_date];
            }
            $rentalsPayments = civicrm_api4('RentalsPayment', 'get', [
                'select' => [
                    'SUM(amount)',
                    'AVG(amount)',
                    'COUNT(amount)',
                ],
                'where' => $paymentWhere,
                'checkPermissions' => FALSE,
                'groupBy' => [
                    'tenant_id',
                ],
            ]);
            $rentalsInvoices = civicrm_api4('RentalsInvoice', 'get', [
                'select' => [
                    'SUM(amount)',
                    'AVG(amount)',
                    'COUNT(amount)',
                    'rental_id.tenant_id'
                ],
                'where' => $invoiceWhere,
                'checkPermissions' => FALSE,
                'groupBy' => [
                    'rental_id.tenant_id',
                ],
            ]);
            if(!empty($rentalsPayments)){
                $rentalsPayments = $rentalsPayments[0];
            }
            if(!empty($rentalsInvoices)){
                $rentalsInvoices = $rentalsInvoices[0];
            }
            $payment_amount_sum = $rentalsPayments['SUM:amount'];
            $payment_amount_count = $rentalsPayments['COUNT:amount'];
            $payment_amount_avg = $rentalsPayments['AVG:amount'];
            $invoice_amount_sum = $rentalsInvoices['SUM:amount'];
            $invoice_amount_count = $rentalsInvoices['COUNT:amount'];
            $invoice_amount_avg = $rentalsInvoices['AVG:amount'];
//            U::writeLog($rentalsPayments, '$rentalsPayments');
//            U::writeLog($payment_amount_sum, '$rentalsPayments');
//            U::writeLog($payment_amount_count, '$rentalsPayments');
//            U::writeLog($payment_amount_avg, '$rentalsPayments');
//            U::writeLog($rentalsInvoices, '$rentalsInvoices');
            $url = CRM_Utils_System::url('civicrm/contact/view',
                ['reset' => 1, 'cid' => $tenant_id, 'selectedChild' => 'contact_rentals_tab']);
            $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
            $rows[$rowNum]['civicrm_contact_sort_name_hover'] = ts("This contact.");

            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_count'] = $payment_amount_count;
            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_sum'] = $payment_amount_sum;
            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_avg'] = $payment_amount_avg;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_count'] = $invoice_amount_count;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_sum'] = $invoice_amount_sum;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_avg'] = $invoice_amount_avg;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_balance'] = $payment_amount_sum - $invoice_amount_sum;
            $total_payment_amount_sum = $total_payment_amount_sum + $payment_amount_sum;
            $total_invoice_amount_sum = $total_invoice_amount_sum + $invoice_amount_sum;
            $total_payment_amount_count = $total_payment_amount_count + $payment_amount_count;
            $total_invoice_amount_count = $total_invoice_amount_count + $invoice_amount_count;
            $entryFound = TRUE;
        } else {

            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_count'] = $total_payment_amount_count;
            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_sum'] = $total_payment_amount_sum;
            $total_payment_amount_count = $total_payment_amount_count != 0 ? $total_payment_amount_count : 1;
            $rows[$rowNum]['civicrm_o8_rental_payment_payment_amount_avg'] = round($total_payment_amount_sum / $total_payment_amount_count);
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_count'] = $total_invoice_amount_count;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_sum'] = $total_invoice_amount_sum;
            $total_invoice_amount_count = $total_invoice_amount_count != 0 ? $total_invoice_amount_count : 1;
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_avg'] = round($total_invoice_amount_sum / $total_invoice_amount_count);
            $rows[$rowNum]['civicrm_o8_rental_invoice_invoice_amount_balance'] = $total_payment_amount_sum - $total_invoice_amount_sum;

        }
        // have the column we need
        if (!$entryFound) {
            break;
        }
//        U::writeLog($rows[$rowNum], $rowNum);
    }
}

}
