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
use CRM_Hprentals_ExtensionUtil as E;

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */
class CRM_Hprentals_Form_Report_PaymentDetail extends CRM_Report_Form
{

    protected $_addressField = FALSE;

    protected $_emailField = FALSE;

    protected $_summary = NULL;

//  protected $_customGroupExtends = array('Membership');
    protected $_customGroupGroupBy = FALSE;

    function __construct()
    {
        $this->_columns = [
            'civicrm_o8_rental_payment' => [
                'dao' => 'CRM_Hprentals_DAO_RentalsPayment',
                'fields' => [
                    'payment_id' => [
                        'name' => 'id',
                        'title' => E::ts('Rental ID'),
                        'type' => CRM_Utils_Type::T_INT,
                        'default' => TRUE,
                        'required' => TRUE,
                    ],
                    'tenant_id' => [
                        'name' => 'tenant_id',
                        'title' => ts('Contact Name'),
                        'no_repeat' => FALSE,
                        'default' => TRUE,
                        'required' => TRUE,
                    ],
                    'amount' => [
                        'name' => 'amount',
                        'title' => E::ts('Amount'),
                        'type' => CRM_Utils_Type::T_MONEY,
                        'default' => TRUE,
                        'required' => TRUE,
                    ],
                    'payment_created_id' => [
                        'name' => 'created_id',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Created By'),
                    ],
                    'payment_created_date' => [
                        'name' => 'created_date',
                        'type' => CRM_Utils_Type::T_TIMESTAMP,
                        'title' => E::ts('Created At'),
                    ],
                    'payment_modified_id' => [
                        'name' => 'modified_id',
                        'type' => CRM_Utils_Type::T_INT,
                        'title' => E::ts('Modified By'),
                    ],
                    'payment_modified_date' => [
                        'name' => 'modified_date',
                        'type' => CRM_Utils_Type::T_TIMESTAMP,
                        'title' => E::ts('Modified At'),
                    ],
                ],
                'filters' => [
                    'amount' => [
                        'name' => 'amount',
                        'title' => E::ts('Amount'),
                        'operatorType' => CRM_Report_Form::OP_INT],
                ],
                'order_bys' => [
                    'tr_id' => [
                        'name' => 'id',
                        'title' => ts('Rental ID'),
                        'default' => TRUE,
                        'default_weight' => '1',
                        'default_order' => 'ASC',
                    ],
                    'admission' => [
                        'name' => 'admission',
                        'title' => ts('Admission'),
                    ],
                    'discharge' => [
                        'name' => 'discharge',
                        'title' => ts('Discharge'),
                    ],
                    'payment_created_date' => [
                        'title' => ts('Created At'),
                    ],
                    'payment_modified_date' => [
                        'title' => ts('Modified At'),
                    ],
                ],
                'grouping' => 'rental-fields',
            ],
            'civicrm_tenant' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' =>
                    array_merge(
                        $this->getBasicContactFields(),
                        [
                            'sort_name' => [
                                'title' => ts('Contact Name'),
                                'no_display' => TRUE,
                                'no_repeat' => FALSE,
                                'default' => TRUE,
                                'required' => TRUE,
                            ],
                        ]
                    ),

                'filters' => $this->getBasicContactFilters(['deceased' => NULL]),
                'grouping' => 'contact-fields',
                'group_bys' => [
//                    'id' => [
//                        'title' => ts('Contact ID'),
//                        'default' => TRUE],
//                    'sort_name' => [
//                        'title' => ts('Contact Name'),
//                    ],
                ],
            ],
            'civicrm_created' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => [
                    'created_sort_name' => [
                        'no_display' => TRUE,
                        'name' => 'sort_name',
                        'default' => TRUE,
                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'created_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Created By'),
                        'operator' => 'like',
                    ],
                ],
                'grouping' => 'fund-fields',
            ],
            'civicrm_modified' => [
                'dao' => 'CRM_Contact_DAO_Contact',
                'fields' => [
                    'modified_sort_name' => [
                        'no_display' => TRUE,
                        'name' => 'sort_name',
                        'default' => TRUE,
                        'required' => TRUE,
                    ],
                ],
                'filters' => [
                    'modified_sort_name' => [
                        'name' => 'sort_name',
                        'title' => E::ts('Modified By'),
                        'operator' => 'like',
                    ],
                ],
                'grouping' => 'fund-fields',
            ],


        ];
        parent::__construct();
    }

    function preProcess()
    {
        $this->assign('reportTitle', E::ts('Funds Detail Report'));
        parent::preProcess();
    }

    function from()
    {
        $this->_from = NULL;

        $from = $this->_from;
        $this->_from = $from . "FROM civicrm_o8_rental_payment {$this->_aliases['civicrm_o8_rental_payment']}
               INNER JOIN civicrm_contact {$this->_aliases['civicrm_tenant']}
                     ON {$this->_aliases['civicrm_tenant']}.id 
                     = {$this->_aliases['civicrm_o8_rental_payment']}.tenant_id 
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_created']}
                          ON {$this->_aliases['civicrm_created']}.id =
                             {$this->_aliases['civicrm_o8_rental_payment']}.created_id
               LEFT JOIN civicrm_contact {$this->_aliases['civicrm_modified']}
                          ON {$this->_aliases['civicrm_modified']}.id =
                             {$this->_aliases['civicrm_o8_rental_payment']}.modified_id
                     ";

    }

    /**
     * Add field specific select alterations.
     *
     * @param string $tableName
     * @param string $tableKey
     * @param string $fieldName
     * @param array $field
     *
     * @return string
     */
    function selectClause(&$tableName, $tableKey, &$fieldName, &$field)
    {
        return parent::selectClause($tableName, $tableKey, $fieldName, $field);
    }

    /**
     * Add field specific where alterations.
     *
     * This can be overridden in reports for special treatment of a field
     *
     * @param array $field Field specifications
     * @param string $op Query operator (not an exact match to sql)
     * @param mixed $value
     * @param float $min
     * @param float $max
     *
     * @return null|string
     */
    public function whereClause(&$field, $op, $value, $min, $max)
    {
        return parent::whereClause($field, $op, $value, $min, $max);
    }

    function alterDisplay(&$rows)
    {
        // custom code to alter rows
        $entryFound = FALSE;
        $checkList = [];
//        U::writeLog($rows, 'rows');
        foreach ($rows as $rowNum => $row) {

            if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
                // not repeat contact display names if it matches with the one
                // in previous row
                $repeatFound = FALSE;
                foreach ($row as $colName => $colVal) {
                    if (CRM_Utils_Array::value($colName, $checkList) &&
                        is_array($checkList[$colName]) &&
                        in_array($colVal, $checkList[$colName])
                    ) {
                        $rows[$rowNum][$colName] = $colVal;
                        $repeatFound = TRUE;
                    }
                    if (in_array($colName, $this->_noRepeats)) {
                        $checkList[$colName][] = $colVal;
                    }
                }
            }

            if (array_key_exists('civicrm_o8_rental_payment_rental_id', $row) &&
                $rows[$rowNum]['civicrm_o8_rental_payment_rental_id']) {
                $url = CRM_Utils_System::url("civicrm/rentals/rental",
                    'reset=1&id=' . $row['civicrm_o8_rental_payment_rental_id'] . "&action=preview",
                    $this->_absoluteUrl
                );
                $rows[$rowNum]['civicrm_o8_rental_payment_rental_id_link'] = $url;
                $rows[$rowNum]['civicrm_o8_rental_payment_rental_id_hover'] = E::ts("View Summary for this Rental.");
                $entryFound = TRUE;
//                unset($rows[$rowNum]['sort_name']);
            }
            if (isset($row['civicrm_o8_rental_payment_tenant_id']) &&
                isset($row['civicrm_tenant_sort_name'])) {
                $url = CRM_Utils_System::url("civicrm/contact/view",
                    'reset=1&cid=' . $row['civicrm_o8_rental_payment_tenant_id'],
                    $this->_absoluteUrl
                );
                $rows[$rowNum]['civicrm_o8_rental_payment_tenant_id'] = $rows[$rowNum]['civicrm_tenant_sort_name'];
                $rows[$rowNum]['civicrm_o8_rental_payment_tenant_id_link'] = $url;
                $rows[$rowNum]['civicrm_o8_rental_payment_tenant_id_hover'] = E::ts("View Summary for this Contact.");
                $entryFound = TRUE;
            }
            if (isset($row['civicrm_o8_rental_payment_payment_created_id']) &&
                isset($row['civicrm_created_created_sort_name'])) {
                $url = CRM_Utils_System::url("civicrm/contact/view",
                    'reset=1&cid=' . $row['civicrm_o8_rental_payment_created_id'],
                    $this->_absoluteUrl
                );
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_created_id'] = $rows[$rowNum]['civicrm_created_created_sort_name'];
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_created_id_link'] = $url;
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_created_id_hover'] = E::ts("View Summary for this Contact.");
                $entryFound = TRUE;
            }
            if (isset($row['civicrm_o8_rental_payment_payment_modified_id']) &&
                isset($row['civicrm_modified_sort_name'])) {
                $url = CRM_Utils_System::url("civicrm/contact/view",
                    'reset=1&cid=' . $row['civicrm_o8_rental_payment_modified_id'],
                    $this->_absoluteUrl
                );
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_modified_id'] = $rows[$rowNum]['civicrm_modified_sort_name'];
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_modified_id_link'] = $url;
                $rows[$rowNum]['civicrm_o8_rental_payment_payment_modified_id_hover'] = E::ts("View Summary for this Contact.");
                $entryFound = TRUE;
            }

            if (!$entryFound) {
                break;
            }
        }
    }

}
