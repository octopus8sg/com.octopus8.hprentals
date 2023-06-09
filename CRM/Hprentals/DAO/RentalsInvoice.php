<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.hprentals/xml/schema/CRM/Hprentals/04RentalsInvoice.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:e74567eba0b22abba2ca758f2afd0fb6)
 */
use CRM_Hprentals_ExtensionUtil as E;

/**
 * Database access object for the RentalsInvoice entity.
 */
class CRM_Hprentals_DAO_RentalsInvoice extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_rental_invoice';

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
    'add' => 'civicrm/rentals/anginvoice',
    'update' => 'civicrm/rentals/invoice?action=update&id=[id]',
    'delete' => 'civicrm/rentals/invoice?action=delete&id=[id]',
    'view' => 'civicrm/rentals/invoice?action=view&id=[id]',
    'tadd' => 'civicrm/rentals/anginvoice#?cid=[id]',
    'tupdate' => 'civicrm/rentals/invoice?action=update&id=[id]&cid=[cid]',
    'tdelete' => 'civicrm/rentals/invoice?action=delete&id=[id]&cid=[cid]',
    'tview' => 'civicrm/rentals/invoice?action=view&id=[id]&cid=[cid]',
  ];

  /**
   * Unique RentalsInvoice ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Code
   *
   * @var string|null
   *   (SQL type: varchar(12))
   *   Note that values will be retrieved from the database as a string.
   */
  public $code;

  /**
   * Name
   *
   * @var string
   *   (SQL type: varchar(1255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $description;

  /**
   * FK to RentalsRental
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $rental_id;

  /**
   * Amount
   *
   * @var float|string
   *   (SQL type: decimal(20,2))
   *   Note that values will be retrieved from the database as a string.
   */
  public $amount;

  /**
   * Invoice date
   *
   * @var string|null
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $start_date;

  /**
   * Invoice End Date
   *
   * @var string|null
   *   (SQL type: date)
   *   Note that values will be retrieved from the database as a string.
   */
  public $end_date;

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
    $this->__table = 'civicrm_o8_rental_invoice';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Rentals Invoices') : E::ts('Rentals Invoice');
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'rental_id', 'civicrm_o8_rental_rental', 'id');
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
          'description' => E::ts('Unique RentalsInvoice ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_rental_invoice.id',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'code' => [
          'name' => 'code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Code'),
          'description' => E::ts('Code'),
          'maxlength' => 12,
          'size' => CRM_Utils_Type::TWELVE,
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_invoice.code',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Code"),
          ],
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Description'),
          'description' => E::ts('Name'),
          'required' => TRUE,
          'maxlength' => 1255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_o8_rental_invoice.description',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'TextArea',
            'label' => E::ts("Description"),
          ],
          'add' => NULL,
        ],
        'rental_id' => [
          'name' => 'rental_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Rental'),
          'description' => E::ts('FK to RentalsRental'),
          'where' => 'civicrm_o8_rental_invoice.rental_id',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'FKClassName' => 'CRM_Hprentals_DAO_RentalsRental',
          'html' => [
            'type' => 'Select',
            'label' => E::ts("Rental"),
          ],
          'pseudoconstant' => [
            'table' => 'civicrm_o8_rental_rental',
            'keyColumn' => 'id',
            'labelColumn' => 'code',
          ],
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
          'where' => 'civicrm_o8_rental_invoice.amount',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => TRUE,
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
            'label' => E::ts("Amount"),
          ],
          'add' => NULL,
        ],
        'start_date' => [
          'name' => 'start_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('Start Date'),
          'description' => E::ts('Invoice date'),
          'where' => 'civicrm_o8_rental_invoice.start_date',
          'default' => NULL,
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDate',
            'label' => E::ts("Start date"),
          ],
          'add' => NULL,
        ],
        'end_date' => [
          'name' => 'end_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => E::ts('End Date'),
          'description' => E::ts('Invoice End Date'),
          'where' => 'civicrm_o8_rental_invoice.end_date',
          'default' => NULL,
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'formatType' => 'activityDate',
            'label' => E::ts("End date"),
          ],
          'add' => NULL,
        ],
        'created_id' => [
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Created By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who created this'),
          'where' => 'civicrm_o8_rental_invoice.created_id',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Created By"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'description' => E::ts('Date and time this was created.'),
          'where' => 'civicrm_o8_rental_invoice.created_date',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'label' => E::ts("Created Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'modified_id' => [
          'name' => 'modified_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Modified By Contact ID'),
          'description' => E::ts('FK to civicrm_contact, who modified this'),
          'where' => 'civicrm_o8_rental_invoice.modified_id',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
            'label' => E::ts("Modified By"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'modified_date' => [
          'name' => 'modified_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Modified Date'),
          'description' => E::ts('Date and time this was modified.'),
          'where' => 'civicrm_o8_rental_invoice.modified_date',
          'table_name' => 'civicrm_o8_rental_invoice',
          'entity' => 'RentalsInvoice',
          'bao' => 'CRM_Hprentals_DAO_RentalsInvoice',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
            'label' => E::ts("Modified Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_rental_invoice', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_rental_invoice', $prefix, []);
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
      'index_code' => [
        'name' => 'index_code',
        'field' => [
          0 => 'code',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_o8_rental_invoice::1::code',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
