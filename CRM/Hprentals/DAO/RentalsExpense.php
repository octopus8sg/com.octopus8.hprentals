<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.hprentals/xml/schema/CRM/Hprentals/01RentalsExpense.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:e0335cf4212ca4d673a89c2e4eaa0c13)
 */
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Database access object for the RentalsExpense entity.
 */
class CRM_Hprentals_DAO_RentalsExpense extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_rental_expense';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Paths for accessing this entity in the UI.
   *
   * @var string[]
   */
  protected static $_paths = [
    'add' => 'civicrm/rentals/expense?reset=1&action=add',
    'view' => 'civicrm/rentals/expense?reset=1&action=view&id=[id]',
    'update' => 'civicrm/rentals/expense?reset=1&action=update&id=[id]',
    'delete' => 'civicrm/rentals/expense?reset=1&action=delete&id=[id]',
  ];

  /**
   * Unique Rental Expense ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Name
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * @var string
   *   (SQL type: varchar(25))
   *   Note that values will be retrieved from the database as a string.
   */
  public $frequency;

  /**
   * Is Refund?
   *
   * @var bool|string|null
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_refund;

  /**
   * Is prorate?
   *
   * @var bool|string|null
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_prorate;

  /**
   * Amount
   *
   * @var float|string
   *   (SQL type: decimal(20,2))
   *   Note that values will be retrieved from the database as a string.
   */
  public $amount;

  /**
   * FK to civicrm_contact, who created this
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_id;

  /**
   * Date and time this was created.
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_date;

  /**
   * FK to civicrm_contact, who modified this
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_id;

  /**
   * Date and time this was modified.
   *
   * @var string|null
   *   (SQL type: datetime)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_date;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_rental_expense';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Rentals Expenses') : E::ts('Rentals Expense');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'created_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'modified_id', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique Rental Expense ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_expense.id',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('Name'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_expense.name',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Name"),
          ],
          'add' => NULL,
        ],
        'frequency' => [
          'name' => 'frequency',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Frequency'),
          'required' => TRUE,
          'maxlength' => 25,
          'size' => CRM_Utils_Type::MEDIUM,
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_expense.frequency',
          'export' => TRUE,
          'default' => 'once_off',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Hprentals_Utils::getexpenseFrequency',
          ],
          'add' => NULL,
        ],
        'is_refund' => [
          'name' => 'is_refund',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Refund'),
          'description' => E::ts('Is Refund?'),
          'where' => 'civicrm_o8_rental_expense.is_refund',
          'default' => '0',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'add' => NULL,
        ],
        'is_prorate' => [
          'name' => 'is_prorate',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Prorate'),
          'description' => E::ts('Is prorate?'),
          'where' => 'civicrm_o8_rental_expense.is_prorate',
          'default' => '1',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'add' => NULL,
        ],
        'amount' => [
          'name' => 'amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => E::ts('Amount'),
          'description' => E::ts('Amount'),
          'required' => TRUE,
          'precision' => [
            20,
            2,
          ],
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_expense.amount',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Amount"),
          ],
          'add' => NULL,
        ],
        'created_id' => [
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Created By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who created this'),
          'where' => 'civicrm_o8_rental_expense.created_id',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'label' => E::ts("Created By"),
          ],
          'add' => NULL,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'description' => E::ts('Date and time this was created.'),
          'where' => 'civicrm_o8_rental_expense.created_date',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'add' => '3.0',
        ],
        'modified_id' => [
          'name' => 'modified_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Modified By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who modified this'),
          'where' => 'civicrm_o8_rental_expense.modified_id',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'label' => E::ts("Modified By"),
          ],
          'add' => NULL,
        ],
        'modified_date' => [
          'name' => 'modified_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Modified Date'),
          'description' => E::ts('Date and time this was modified.'),
          'where' => 'civicrm_o8_rental_expense.modified_date',
          'table_name' => 'civicrm_o8_rental_expense',
          'entity' => 'RentalsExpense',
          'bao' => 'CRM_Hprentals_DAO_RentalsExpense',
          'localizable' => 0,
          'add' => '3.0',
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_rental_expense', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_rental_expense', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_name' => [
        'name' => 'index_name',
        'field' => [
          0 => 'name',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_o8_rental_expense::1::name',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
